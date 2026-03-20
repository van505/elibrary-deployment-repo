<?php

namespace App\Http\Controllers\Member;

use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubscriptionController extends BaseMemberController
{
    public function index()
    {
        $plans               = SubscriptionPlan::where('is_active', true)->orderBy('price')->get();
        $member              = $this->getOrCreateMember();
        $currentSubscription = $member->activeSubscription();

        return view('member.subscriptions.index', compact('plans', 'member', 'currentSubscription'));
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'plan_id'        => 'required|exists:subscription_plans,id',
            'payment_method' => 'nullable|in:cash,gcash,maya,credit_card',
        ]);

        $member = $this->getOrCreateMember();
        $plan   = SubscriptionPlan::findOrFail($request->plan_id);

        // Guard: already on this exact plan
        $alreadyOnThisPlan = $member->subscriptions()
            ->where('status', 'active')
            ->where('plan_id', $plan->id)
            ->exists();

        if ($alreadyOnThisPlan) {
            return redirect()->route('member.subscriptions.index')
                ->with('error', 'You are already subscribed to the ' . $plan->name . ' plan.');
        }

        // Cancel current active subscriptions
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
