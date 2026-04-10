<?php

namespace App\Http\Controllers\Admin;

use App\Services\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Traits\HandlesAdminFilters;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    use HandlesAdminFilters;

    public function index(Request $request)
    {
        $query = Subscription::with(['member.user', 'plan']);
        $query = $this->applyFilters($query, $request, 'filter_subscriptions', ['member.full_name'], ['plan_id', 'status']);

        $subscriptions = $query->paginate(15)->appends($request->query());
        $plans = SubscriptionPlan::orderBy('name')->get();

        return view('admin.subscriptions.index', compact('subscriptions', 'plans'));
    }

    public function show(Subscription $subscription)
    {
        $subscription->load('member.user', 'plan');
        $transactions = $subscription->member->transactions()
            ->where('plan_id', $subscription->plan_id)
            ->latest()
            ->get();
        return view('admin.subscriptions.show', compact('subscription', 'transactions'));
    }

    public function update(Request $request, Subscription $subscription)
    {
        $request->validate([
            'status' => 'required|in:active,expired,cancelled',
        ]);

        $subscription->update(['status' => $request->status]);
        ActivityLogger::log('updated', 'subscriptions', 'Updated subscription status to: ' . $request->status);
        return redirect()->route('admin.subscriptions.index')->with('success', 'Subscription updated.');
    }
}
