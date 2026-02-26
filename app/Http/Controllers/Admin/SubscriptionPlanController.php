<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;

class SubscriptionPlanController extends Controller
{
     public function index()
    {
        $plans = SubscriptionPlan::all();
        return view('subscription_plans.index', compact('plans'));
    }

    public function create()
    {
        return view('subscription_plans.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:subscription_plans,name',
            'validity_days' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'features' => 'nullable|string',
        ]);

        SubscriptionPlan::create($request->all());
        return redirect()->route('admin.subscriptions_plans.index')->with('success', 'Plan created successfully.');
    }

    public function edit(SubscriptionPlan $plan)
    {
        return view('subscription_plans.edit', compact('plan'));
    }

    public function update(Request $request, SubscriptionPlan $plan)
    {
        $request->validate([
            'name' => 'required|unique:subscription_plans,name,' . $plan->id,
            'validity_days' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'features' => 'nullable|string',
        ]);

        $plan->update($request->all());
        return redirect()->route('admin.subscriptions_plans.index')->with('success', 'Plan updated successfully.');
    }

    public function destroy(SubscriptionPlan $plan)
    {
        $plan->delete();
        return redirect()->route('admin.subscriptions_plans.index')->with('success', 'Plan deleted successfully.');
    }
}
