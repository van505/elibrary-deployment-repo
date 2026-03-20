<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubscriptionController extends Controller
{
    public function index()
    {
        $plans               = SubscriptionPlan::where('is_active', true)->orderBy('price')->get();
        $member              = auth()->user()->member;
        $currentSubscription = $member ? $member->activeSubscription() : null;

        return view('member.subscriptions.index', compact('plans', 'member', 'currentSubscription'));
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'plan_id'        => 'required|exists:subscription_plans,id',
            'payment_method' => 'nullable|in:cash,gcash,maya,credit_card',
        ]);

        $member = auth()->user()->member;
        $plan   = SubscriptionPlan::findOrFail($request->plan_id);

        // Cancel any existing active subscriptions
        $member->subscriptions()->where('status', 'active')->update(['status' => 'cancelled']);

        // Create new subscription
        Subscription::create([
            'member_id'  => $member->id,
            'plan_id'    => $plan->id,
            'status'     => 'active',
            'started_at' => now(),
            'expires_at' => $plan->price > 0 ? now()->addMonth() : null,
        ]);

        // Record transaction for paid plans
        if ($plan->price > 0) {
            Transaction::create([
                'member_id'      => $member->id,
                'plan_id'        => $plan->id,
                'amount'         => $plan->price,
                'payment_method' => $request->payment_method ?? 'cash',
                'status'         => 'completed',
                'reference_no'   => 'TXN-' . strtoupper(Str::random(8)),
                'paid_at'        => now(),
            ]);
        }

        return redirect()->route('member.dashboard')
            ->with('success', 'Successfully subscribed to ' . $plan->name . ' plan!');
    }
}
