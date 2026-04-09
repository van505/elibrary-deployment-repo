<?php

namespace App\Http\Controllers\Admin;

use App\Services\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::with(['member.user', 'plan'])
            ->latest()
            ->paginate(15);
        return view('admin.subscriptions.index', compact('subscriptions'));
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
