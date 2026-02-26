<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\AppUser;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Mail;
use App\Mail\BaseNotification;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    protected $otpLength = 4;
    protected $otpTtlMinutes = 5;
    protected $maxOtpAttempts = 3;
    protected $otpBlockMinutes = 60;

    /* ---------------- SHOW LOGIN ---------------- */
    public function showLogin()
    {
        return view('web.auth.login');
    }

    /* ---------------- SEND OTP ---------------- */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'mobile' => 'required|digits:10'
        ]);

        $mobile = $request->mobile;

        $sendKey    = "otp_send_count_{$mobile}";
        $blockedKey = "otp_blocked_{$mobile}";
        $otpKey     = "otp_{$mobile}";

        // 🚫 Hard block
        if (Cache::has($blockedKey)) {
            return back()->withErrors(['mobile' => 'OTP blocked for 1 hour']);
        }

        $sendCount = Cache::get($sendKey, 0);

        if ($sendCount >= $this->maxOtpAttempts) {
            Cache::put($blockedKey, true, now()->addMinutes($this->otpBlockMinutes));
            return back()->withErrors(['mobile' => 'OTP limit exceeded']);
        }

        // 🔢 Generate OTP
        $otp = str_pad(random_int(0, 9999), $this->otpLength, '0', STR_PAD_LEFT);

        Cache::put($otpKey, $otp, now()->addMinutes($this->otpTtlMinutes));
        Cache::put($sendKey, $sendCount + 1, now()->addMinutes($this->otpBlockMinutes));

        // 📲 WhatsApp (skip failure)
        try {
            (new WhatsAppService())->sendOTPMessage($mobile, $otp);
        } catch (\Exception $e) {
            Log::error('WhatsApp OTP failed', ['mobile' => $mobile]);
        }

        // 📧 Email OTP (only if user + email exists)
        $user = AppUser::where('mobile', $mobile)->first();
        if ($user && $user->email) {
            try {
                Mail::to($user->email)->send(
                    new BaseNotification(
                        'Your Login OTP',
                        ['message' => "Your OTP is {$otp}"]
                    )
                );
            } catch (\Exception $e) {
                Log::error('Email OTP failed', ['email' => $user->email]);
            }
        }

        // 🔥 DEV ONLY: expose OTP safely
        if (app()->environment('local')) {
            session(['dev_otp' => $otp]);
            Log::info('DEV OTP', ['mobile' => $mobile, 'otp' => $otp]);
        }

        session(['otp_mobile' => $mobile]);

        return redirect('/verify-otp');
    }

    /* ---------------- SHOW OTP VERIFY ---------------- */
    public function showVerifyOtp()
    {
        if (!session('otp_mobile')) {
            return redirect('/login');
        }

        return view('web.auth.verify-otp');
    }

    /* ---------------- VERIFY OTP ---------------- */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:4'
        ]);

        $mobile = session('otp_mobile');
        if (!$mobile) {
            return redirect('/login');
        }

        $otpKey     = "otp_{$mobile}";
        $blockedKey = "otp_blocked_{$mobile}";
        $sendKey    = "otp_send_count_{$mobile}";

        $cachedOtp = Cache::get($otpKey);

        if (!$cachedOtp || $cachedOtp !== $request->otp) {
            return back()->withErrors(['otp' => 'Invalid OTP']);
        }

        // ✅ OTP VALID — clear all counters
        Cache::forget($otpKey);
        Cache::forget($blockedKey);
        Cache::forget($sendKey);
        $mobile='91'.$mobile;
        // 🔍 Find or auto-register user
        $user = AppUser::where('mobile', $mobile)->first();

        if (!$user) {
            $user = AppUser::create([
                'name'               => 'User ' . substr($mobile, -4),
                'mobile'             => $mobile,
                'role'               => 'buyer',
                'status'             => 'active',
                'is_mobile_verified' => 1,
            ]);
        }

        if ($user->status === 'blocked') {
            return redirect('/login')->withErrors(['mobile' => 'Account blocked']);
        }

        // ✅ SESSION LOGIN
        Auth::guard('web_portal')->login($user);

        // 🧹 Clean session
        session()->forget(['otp_mobile', 'dev_otp']);

        return redirect('/');
    }

    /* ---------------- LOGOUT ---------------- */
    public function logout()
    {
        Auth::guard('web_portal')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/login');
    }
}
