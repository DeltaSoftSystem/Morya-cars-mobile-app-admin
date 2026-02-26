<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
     public function plans()
    {
        $plans = SubscriptionPlan::all();

        return response()->json([
            'status' => true,
            'message' => 'Subscription plans fetched successfully',
            'data' => $plans
        ]);
    }

    // 2️⃣ Subscribe to a Plan
    public function subscribe(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'plan_id' => 'required'
        ]);

        $plan = SubscriptionPlan::find($request->plan_id);

        if (!$plan) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid subscription plan'
            ]);
        }

        $startDate = Carbon::now();
        $endDate = Carbon::now()->addDays($plan->validity_days);

        $subscription = UserSubscription::create([
            'user_id' => $request->user_id,
            'plan_id' => $request->plan_id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'payment_status' => 'success', // change to pending if integrating real payment
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Subscription activated successfully',
            'data' => $subscription
        ]);
    }

    // 3️⃣ Check User Active Subscription
    public function activePlan($user_id)
    {
        $subscription = UserSubscription::where('user_id', $user_id)
            ->where('end_date', '>=', Carbon::now()->format('Y-m-d'))
            ->where('payment_status', 'paid')
            ->latest()
            ->first();

        if (!$subscription) {
            return response()->json([
                'status' => false,
                'message' => 'No active subscription found'
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Active subscription fetched',
            'data' => $subscription->load('plan')
        ]);
    }
}
