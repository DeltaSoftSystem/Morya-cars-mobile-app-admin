<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubscriptionPayment;
use App\Models\UserSubscription;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Log;
use App\Services\NotificationService;


class RazorpayWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('X-Razorpay-Signature');

        $expectedSignature = hash_hmac(
            'sha256',
            $payload,
            config('services.razorpay.webhook_secret')
        );

        if (!hash_equals($expectedSignature, $signature)) {
            Log::error('Invalid Razorpay webhook signature');
            return response()->json(['error' => 'Invalid signature'], 403);
        }

        $event = json_decode($payload, true);

        if ($event['event'] !== 'payment.captured') {
            return response()->json(['status' => 'ignored']);
        }

        $payment = $event['payload']['payment']['entity'];

        $orderId   = $payment['order_id'];
        $paymentId = $payment['id'];

        $subscriptionPayment = SubscriptionPayment::where(
            'razorpay_order_id',
            $orderId
        )->first();

        // 🛑 Safety check
        if (!$subscriptionPayment || !$subscriptionPayment->subscription_plan_id) {
            Log::error('Plan ID missing for order', [
                'order_id' => $orderId,
                'payment'  => $paymentId,
            ]);
            return response()->json(['error' => 'Invalid order mapping'], 422);
        }

        // ✅ Idempotency (VERY IMPORTANT)
        if ($subscriptionPayment->status === 'paid') {
            return response()->json(['status' => 'already_processed']);
        }

        // ✅ Mark payment as paid
        $subscriptionPayment->update([
            'razorpay_payment_id' => $paymentId,
            'status' => 'paid',
        ]);

        // ✅ Expire old subscriptions
        UserSubscription::where('user_id', $subscriptionPayment->user_id)
            ->where('payment_status', 'paid')
            ->update(['payment_status' => 'expired']);

        // ✅ Fetch plan dynamically
        $plan = SubscriptionPlan::find($subscriptionPayment->subscription_plan_id);

        if (!$plan || !$plan->validity_days) {
            Log::error('Invalid subscription plan or missing validity_days', [
                'plan_id' => $subscriptionPayment->subscription_plan_id
            ]);
            return response()->json(['error' => 'Invalid plan configuration'], 500);
        }

        // ✅ Calculate dates dynamically
        $startDate = now();
        $endDate   = now()->addDays($plan->validity_days);

        // ✅ Create new active subscription
        UserSubscription::create([
            'user_id'        => $subscriptionPayment->user_id,
            'plan_id'        => $subscriptionPayment->subscription_plan_id,
            'start_date'     => $startDate,
            'end_date'       => $endDate,
            'payment_status' => 'paid',
        ]);

        // 📧 SEND SUBSCRIPTION ACTIVATED EMAIL
        $user = $subscriptionPayment->user;
        $plan = $plan; // already fetched above

        NotificationService::sendEmail(
            $user->email,
            'Subscription Activated',
            [
                'name'    => $user->name,
                'message' => "Your '{$plan->name}' subscription has been activated successfully.",
                'extra'   => "Valid from {$startDate->format('d M Y')} to {$endDate->format('d M Y')}."
            ]
        );


        return response()->json(['status' => 'subscription_activated']);
    }
}
