<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Services\WhatsAppService;
use App\Models\AppUser;
use App\Models\UserSubscription;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\BaseNotification;
use Exception;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    protected $otpLength = 4;
    protected $otpTtlMinutes = 5;

    protected $maxOtpAttempts = 3;
    protected $otpBlockMinutes = 60; // 1 hour


    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:app_users,email',
            'mobile' => 'required|string|unique:app_users,mobile',
            'role' => 'required|in:buyer,seller',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user = AppUser::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'role' => $request->role,
            'status' => 'active',
            'is_mobile_verified' => 0,
            'is_email_verified' => 0,
        ]);

        // ✅ EMAIL SHOULD NEVER BREAK API
    if (!empty($user->email)) {
        try {
            Mail::to($user->email)->send(
                new BaseNotification(
                    'Welcome to Morya Auto Hub',
                    [
                        'name' => $user->name,
                        'message' => 'Your account has been created successfully.'
                    ]
                )
            );
        } catch (Exception $e) {
            Log::error('Registration mail failed', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage()
            ]);
            // ❌ DO NOT return error
        }
    }


        return response()->json([
            'status' => 'success',
            'message' => 'User registered successfully',
            'data' => [
                'user' => $user
            ]
        ]);
    }

   public function sendOtp(Request $request)
    {
        $request->validate([
            'mobile' => 'required|string',
        ]);

        $mobile = $request->mobile;

        $sendKey    = 'otp_send_count_' . $mobile;
        $blockedKey = 'otp_blocked_' . $mobile;
        $otpKey     = 'otp_' . $mobile;

        // 🚫 Hard block check
        if (Cache::has($blockedKey)) {
            return response()->json([
                'status' => 'error',
                'message' => 'OTP blocked for 1 hour due to multiple attempts.'
            ], 429);
        }

        // 📊 Get current send count (DO NOT increment yet)
        $sendCount = Cache::get($sendKey, 0);

        /**
         * ✅ ALLOW only first 3 sends
         * ❌ Block on 4th attempt
         */
        if ($sendCount >= $this->maxOtpAttempts) {
            Cache::put($blockedKey, true, now()->addMinutes($this->otpBlockMinutes));

            return response()->json([
                'status' => 'error',
                'message' => 'OTP send limit exceeded. Try again after 1 hour.'
            ], 429);
        }

        // ⏱ Throttle AFTER send-count check
        $throttleKey = 'otp_throttle_' . $mobile;
        if (Cache::has($throttleKey)) {
            return response()->json([
                'status' => 'error',
                'message' => 'OTP recently sent. Please wait.'
            ], 429);
        }

        // 🔢 Generate OTP
        $otp = str_pad(
            random_int(0, (10 ** $this->otpLength) - 1),
            $this->otpLength,
            '0',
            STR_PAD_LEFT
        );

        // 💾 Store OTP
        Cache::put($otpKey, $otp, now()->addMinutes($this->otpTtlMinutes));

        // ✅ Increment send count NOW
        Cache::put(
            $sendKey,
            $sendCount + 1,
            now()->addMinutes($this->otpBlockMinutes)
        );

        // ⏱ 60 sec throttle
        Cache::put($throttleKey, true, now()->addSeconds(60));

        // 📩 Send OTP
        (new WhatsAppService())->sendOTPMessage($mobile, $otp);

        // 📧 Send OTP via Email (if available)
        try {
            $user = AppUser::where('mobile', $mobile)->first();

            if ($user && !empty($user->email)) {
                Mail::to($user->email)->send(
                    new BaseNotification(
                        'Your Morya Auto Hub OTP',
                        [
                            'name' => $user->name ?? 'User',
                            'message' => "Your OTP is: {$otp}. It is valid for {$this->otpTtlMinutes} minutes."
                        ]
                    )
                );
            }
        } catch (Exception $e) {
            Log::error('Email OTP failed', [
                'mobile' => $mobile,
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'OTP sent successfully',
            'data' => [
                'send_count'    => $sendCount + 1,
                'attempts_left' => $this->maxOtpAttempts - ($sendCount + 1)
            ]
        ]);
    }





    /**
     * Verify OTP and issue Sanctum token
     */
    public function verifyOtp(Request $request)
    {
        // 1️⃣ Validate input
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|string',
            'otp'    => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        $mobile = $request->mobile;
        $otp    = $request->otp;

        // Cache keys
        $cacheKey   = 'otp_' . $mobile;
        $failedKey  = 'otp_failed_count_' . $mobile;
        $blockedKey = 'otp_blocked_' . $mobile;
        $sendKey    = 'otp_send_count_' . $mobile;

        // 2️⃣ Check block
        if (Cache::has($blockedKey)) {
            return response()->json([
                'status' => 'error',
                'message' => 'OTP verification blocked. Try again after 1 hour.'
            ], 429);
        }

        // 3️⃣ Verify OTP
        $cachedOtp = Cache::get($cacheKey);

        if (!$cachedOtp || $cachedOtp !== $otp) {

            $failedAttempts = Cache::get($failedKey, 0) + 1;

            Cache::put(
                $failedKey,
                $failedAttempts,
                now()->addMinutes($this->otpBlockMinutes)
            );

            if ($failedAttempts >= $this->maxOtpAttempts) {
                Cache::put(
                    $blockedKey,
                    true,
                    now()->addMinutes($this->otpBlockMinutes)
                );

                Cache::forget($cacheKey);

                return response()->json([
                    'status' => 'error',
                    'message' => 'Too many failed attempts. OTP blocked for 1 hour.'
                ], 429);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Invalid OTP. Attempts left: ' .
                    ($this->maxOtpAttempts - $failedAttempts)
            ], 401);
        }

        // 4️⃣ OTP VALID → clear cache
        Cache::forget($cacheKey);
        Cache::forget($failedKey);
        Cache::forget($blockedKey);
        Cache::forget($sendKey);

        // 5️⃣ Fetch user using ELOQUENT (VERY IMPORTANT)
        $user = AppUser::where('mobile', $mobile)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mobile number not registered.'
            ], 404);
        }

        if ($user->status === 'blocked') {
            return response()->json([
                'status' => 'error',
                'message' => 'Your account is blocked.'
            ], 403);
        }

        // 6️⃣ First-time verification
        // if (!$user->is_mobile_verified) {
        //     $user->is_mobile_verified = true;

        //     // 🎁 Assign free plan ONLY once
        //     $this->assignFreePlan($user->id);
        // }

        // 🔥 IMPORTANT FIX (prevents preg_match error)
        // Force valid timestamps before save
        $user->last_login_ip = $request->ip();
        $user->last_login_at = now()->setTimezone('Asia/Kolkata');
        $user->updated_at   = now()->setTimezone('Asia/Kolkata');

        // 7️⃣ Save user safely
        $user->save();

        // 8️⃣ Create Sanctum token
        $token = $user->createToken('mobile-token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'OTP verified successfully.',
            'data' => [
                'user'       => $user,
                'token'      => $token,
                'token_type' => 'Bearer',
            ]
        ]);
    }





    /**
     * Get profile
     */
    public function profile(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'data' => $request->user()
        ]);
    }

   public function updateProfile(Request $request)
    {
        $authUser = $request->user(); // GenericUser (no update())

        if (!$authUser) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        // 🔥 Convert GenericUser → AppUser model
        $user = AppUser::find($authUser->id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        if ($request->filled('name')) {
            $user->name = $request->name;
            $user->save();
        }

        return response()->json([
            'status' => true,
            'message' => 'Profile updated successfully',
            'data' => $user
        ]);
    }


    public function verifyProfileOtp(Request $request)
{
    $request->validate([
        'type' => 'required|in:email,mobile',
        'otp'  => 'required|string'
    ]);

    $user = $request->user();

    if (!$user) {
        return response()->json([
            'status' => false,
            'message' => 'Unauthenticated'
        ], 401);
    }

    // =====================
    // ✅ MOBILE → CACHE
    // =====================
    if ($request->type === 'mobile') {

        $otpKey     = 'profile_otp_' . $user->id;
        $failedKey  = 'profile_otp_failed_' . $user->id;
        $blockedKey = 'profile_otp_blocked_' . $user->id;

        if (Cache::has($blockedKey)) {
            return response()->json([
                'status' => false,
                'message' => 'OTP blocked due to multiple attempts'
            ], 429);
        }

        $cached = Cache::get($otpKey);

        if (!$cached || $cached['otp'] !== $request->otp) {

            $failed = Cache::get($failedKey, 0) + 1;

            Cache::put($failedKey, $failed, now()->addMinutes(60));

            if ($failed >= 3) {
                Cache::put($blockedKey, true, now()->addMinutes(60));
                Cache::forget($otpKey);
            }

            return response()->json([
                'status' => false,
                'message' => 'Invalid OTP'
            ], 422);
        }

        // ✅ OTP correct → update mobile
        Cache::forget($otpKey);
        Cache::forget($failedKey);
        Cache::forget($blockedKey);

        DB::table('app_users')
            ->where('id', $user->id)
            ->update([
                'mobile' => $cached['mobile'],
                'is_mobile_verified' => 1,
                'updated_at' => now()
            ]);

        return response()->json([
            'status' => true,
            'message' => 'Mobile verified successfully'
        ]);
    }

    // =====================
    // ✅ EMAIL → DB (UNCHANGED)
    // =====================

    $record = DB::table('user_contact_verifications')
        ->where('user_id', $user->id)
        ->where('type', 'email')
        ->where('otp', $request->otp)
        ->whereNull('verified_at')
        ->where('expires_at', '>', now())
        ->first();

    if (!$record) {
        return response()->json([
            'status' => false,
            'message' => 'Invalid or expired OTP'
        ], 422);
    }

    DB::table('user_contact_verifications')
        ->where('id', $record->id)
        ->update(['verified_at' => now()]);

    DB::table('app_users')
        ->where('id', $user->id)
        ->update([
            'email' => $record->value,
            'is_email_verified' => 1,
            'updated_at' => now()
        ]);

    return response()->json([
        'status' => true,
        'message' => 'Email verified successfully'
    ]);
}

    public function sendProfileEmailOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = $request->user();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        $otp = random_int(1000, 9999);

        DB::table('user_contact_verifications')->insert([
            'user_id'    => $user->id,
            'type'       => 'email',
            'value'      => $request->email,
            'otp'        => $otp,
            'expires_at' => now()->addMinutes(10),
            'created_at' => now()
        ]);

        // 📧 EMAIL ONLY
        Mail::to($request->email)->send(
            new BaseNotification(
                'Email Verification OTP',
                [
                    'name' => $user->name,
                    'message' => "Your email verification OTP is {$otp}. Valid for 10 minutes."
                ]
            )
        );

        return response()->json([
            'status' => true,
            'message' => 'OTP sent to email successfully'
        ]);
    }



    /**
     * Logout (revoke current token)
     */
    public function logout(Request $request)
    {
        // revoke current token
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logged out'
        ]);
    }


    //free user subscription plan
    public function assignFreePlan($userId)
    {
        // Check if user already has a subscription
        $alreadySubscribed = UserSubscription::where('user_id', $userId)->exists();

        if ($alreadySubscribed) {
            return; // Prevent duplicate free plans
        }

        $startDate = Carbon::today();
        $endDate   = Carbon::today()->addDays(30);

        UserSubscription::create([
            'user_id'        => $userId,
            'plan_id'        => 1, // Free plan
            'start_date'     => $startDate,
            'end_date'       => $endDate,
            'payment_status' => 'free', // better than "pending"
        ]);
    }
}
