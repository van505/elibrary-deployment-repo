<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubscriptionPlanController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::withCount('subscriptions')->paginate(10);
        return view('admin.subscription-plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.subscription-plans.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|unique:subscription_plans,name',
            'price'       => 'required|numeric|min:0',
            'ebook_limit' => 'required|integer|min:-1',
            'description' => 'nullable|string',
        ]);

        $plan = SubscriptionPlan::create([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'price'       => $request->price,
            'ebook_limit' => $request->ebook_limit,
            'description' => $request->description,
            'is_active'   => $request->boolean('is_active', true),
        ]);

        ActivityLogger::log('created', 'subscription_plans', 'Created plan: ' . $plan->name);
        return redirect()->route('admin.subscription-plans.index')->with('success', 'Plan created successfully.');
    }

    public function edit(SubscriptionPlan $subscriptionPlan)
    {
        return view('admin.subscription-plans.edit', compact('subscriptionPlan'));
    }

    public function update(Request $request, SubscriptionPlan $subscriptionPlan)
    {
        $request->validate([
            'name'        => 'required|string|unique:subscription_plans,name,' . $subscriptionPlan->id,
            'price'       => 'required|numeric|min:0',
            'ebook_limit' => 'required|integer|min:-1',
            'description' => 'nullable|string',
        ]);

        $subscriptionPlan->update([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'price'       => $request->price,
            'ebook_limit' => $request->ebook_limit,
            'description' => $request->description,
            'is_active'   => $request->boolean('is_active', true),
        ]);

        ActivityLogger::log('updated', 'subscription_plans', 'Updated plan: ' . $subscriptionPlan->name);
        return redirect()->route('admin.subscription-plans.index')->with('success', 'Plan updated successfully.');
    }

    public function destroy(SubscriptionPlan $subscriptionPlan)
    {
        $subscriptionPlan->delete();
        ActivityLogger::log('deleted', 'subscription_plans', 'Deleted plan: ' . $subscriptionPlan->name);
        return redirect()->route('admin.subscription-plans.index')->with('success', 'Plan deleted successfully.');
    }
}
