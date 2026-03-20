<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Str;

class BaseMemberController extends Controller
{
    /**
     * Get (or auto-create) the Member record for the authenticated user,
     * and ensure they have at least a free subscription.
     * Checks ALL subscription statuses (not just active) to prevent duplicates.
     */
    protected function getOrCreateMember(): Member
    {
        $user   = auth()->user();
        $member = $user->member;

        if (! $member) {
            $member = Member::create([
                'user_id'     => $user->id,
                'member_code' => 'MBR-' . strtoupper(Str::random(6)),
                'status'      => 'active',
            ]);
        }

        // Only create a free plan if this member has NO subscription at all
        // (checking any status — active, cancelled, expired — avoids duplicates)
        $hasAnySubscription = $member->subscriptions()->exists();

        if (! $hasAnySubscription) {
            $freePlan = SubscriptionPlan::where('slug', 'free')->first();
            if ($freePlan) {
                Subscription::create([
                    'member_id'  => $member->id,
                    'plan_id'    => $freePlan->id,
                    'status'     => 'active',
                    'started_at' => now(),
                    'expires_at' => null,
                ]);
            }
        }

        return $member->refresh();
    }
}
