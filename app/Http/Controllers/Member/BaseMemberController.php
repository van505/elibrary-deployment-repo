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
     * Get the authenticated user's member record, auto-creating it
     * (plus a free subscription) if it doesn't exist yet.
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

        // Auto-assign free plan if this member has no active subscription
        $hasActive = $member->subscriptions()->where('status', 'active')->exists();

        if (! $hasActive) {
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

        // Refresh so relationships are re-loaded after potential creation
        return $member->refresh();
    }
}
