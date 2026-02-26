<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionPayment;

class SubscriptionPaymentController extends Controller
{
    public function createOrder(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id'
        ]);

        $plan = SubscriptionPlan::findOrFail($request->plan_id);

        $amount = $plan->price * 100; // paise

        $response = Http::withBasicAuth(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        )->post('https://api.razorpay.com/v1/orders', [
            'amount' => $amount,
            'currency' => 'INR',
            'receipt' => 'SUB_' . time(),
        ]);

        if (!$response->successful()) {
            return response()->json(['message' => 'Order creation failed'], 500);
        }

        $order = $response->json();

        SubscriptionPayment::create([
            'user_id' => auth()->id(),
            'subscription_plan_id' => $plan->id,
            'razorpay_order_id' => $order['id'],
            'amount' => $plan->price,
            'status' => 'pending'
        ]);

        return response()->json([
            'order_id' => $order['id'],
            'amount' => $amount,
            'currency' => 'INR',
            'key' => config('services.razorpay.key'),
            'plan_name' => $plan->name
        ]);
    }

     public function verifyPayment(Request $request)
  {
      $request->validate([
          'razorpay_order_id' => 'required',
          'razorpay_payment_id' => 'required',
      ]);

      // Just store payment_id (DO NOT mark paid yet)
      SubscriptionPayment::where('razorpay_order_id', $request->razorpay_order_id)
          ->update([
              'razorpay_payment_id' => $request->razorpay_payment_id,
              'status' => 'processing',
          ]);

      return response()->json([
          'status' => true,
          'message' => 'Payment received. Awaiting confirmation.'
      ]);
  }
}
