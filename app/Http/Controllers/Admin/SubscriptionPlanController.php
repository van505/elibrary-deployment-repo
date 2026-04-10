<?php

namespace App\Http\Controllers\Admin;

use App\Services\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Traits\HandlesAdminFilters;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubscriptionPlanController extends Controller
{
    use HandlesAdminFilters;

    public function index(Request $request)
    {
        $query = SubscriptionPlan::withCount('subscriptions');
        $query = $this->applyFilters($query, $request, 'filter_subscription_plans', ['name']);

        $plans = $query->paginate(10)->appends($request->query());
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
        // Guard: prevent archiving if plan has active subscriptions
        $activeCount = $subscriptionPlan->subscriptions()->where('status', 'active')->count();
        if ($activeCount > 0) {
            return redirect()->route('admin.subscription-plans.index')
                ->with('error', "Cannot archive this plan — it has {$activeCount} active subscriber(s). Cancel their subscriptions first.");
        }

        ActivityLogger::log('deleted', 'subscription_plans', 'Archived plan: ' . $subscriptionPlan->name);
        $subscriptionPlan->delete(); // soft delete
        return redirect()->route('admin.subscription-plans.index')->with('success', 'Plan archived successfully.');
    }
}
