<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\UserSubscription;
use Carbon\Carbon;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
          $user = auth()->user();

        $subscription = UserSubscription::where('user_id', $user->id)
            ->latest('end_date')
            ->first();

        if (!$subscription) {
            return response()->json([
                'status' => 'error',
                'message' => 'No active subscription found.'
            ], 403);
        }

        if (now()->gt($subscription->end_date)) {
            return response()->json([
                'status' => 'expired',
                'message' => 'Your free trial has expired. Please upgrade.'
            ], 403);
        }

        return $next($request);
    }
}
