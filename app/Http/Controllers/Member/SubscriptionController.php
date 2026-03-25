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
        $plans               = SubscriptionPlan::where('is_active', true)->orderBy('level')->get();
        $member              = $this->getOrCreateMember();
        $currentSubscription = $member->activeSubscription();

        // Past subscriptions (cancelled/expired) for history
        $history = $member->subscriptions()
            ->whereIn('status', ['cancelled', 'expired'])
            ->with('plan')
            ->latest()
            ->get();

        return view('member.subscriptions.index', compact('plans', 'member', 'currentSubscription', 'history'));
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'plan_id'        => 'required|exists:subscription_plans,id',
            'payment_method' => 'nullable|in:cash,gcash,maya,credit_card',
        ]);

        $member  = $this->getOrCreateMember();
        $newPlan = SubscriptionPlan::findOrFail($request->plan_id);

        // Check for an active (non-expired) subscription
        $activeSub = $member->subscriptions()->active()->first();

        if ($activeSub) {
            $currentPlan = $activeSub->plan;

            // Block same plan or downgrade
            if ($newPlan->level <= $currentPlan->level) {
                $expiryNote = $activeSub->expires_at
                    ? ' or wait until it expires on ' . $activeSub->expires_at->format('M d, Y')
                    : '';

                return redirect()->route('member.subscriptions.index')
                    ->with('error',
                        'You already have an active ' . $currentPlan->name . ' subscription. '
                        . 'You can only upgrade to a higher plan' . $expiryNote . '.'
                    );
            }

            // Upgrade: cancel current
            $activeSub->update(['status' => 'cancelled']);
        }

        // Create new subscription
        $newSub = Subscription::create([
            'member_id'  => $member->id,
            'plan_id'    => $newPlan->id,
            'status'     => 'active',
            'started_at' => now(),
            'expires_at' => $newPlan->price > 0 ? now()->addMonth() : null,
        ]);

        // Record transaction for paid plans
        if ($newPlan->price > 0) {
            Transaction::create([
                'member_id'      => $member->id,
                'plan_id'        => $newPlan->id,
                'amount'         => $newPlan->price,
                'payment_method' => $request->payment_method ?? 'cash',
                'status'         => 'completed',
                'reference_no'   => 'TXN-' . strtoupper(Str::random(8)),
                'paid_at'        => now(),
            ]);
        }

        return redirect()->route('member.dashboard')
            ->with('success', 'Successfully subscribed to ' . $newPlan->name . ' plan!');
    }
}
