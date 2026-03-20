<?php

namespace App\Observers;

use App\Models\Member;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Support\Str;

class UserObserver
{
    /**
     * Auto-create a Member record and a free Subscription when a member registers.
     */
    public function created(User $user): void
    {
        if ($user->role === 'member') {
            $member = Member::create([
                'user_id'     => $user->id,
                'member_code' => 'MBR-' . strtoupper(Str::random(6)),
                'phone'       => null,
                'address'     => null,
                'status'      => 'active',
            ]);

            // Auto-assign the free subscription plan
            $freePlan = SubscriptionPlan::where('slug', 'free')->first();

            if ($freePlan) {
                Subscription::create([
                    'member_id'  => $member->id,
                    'plan_id'    => $freePlan->id,
                    'status'     => 'active',
                    'started_at' => now(),
                    'expires_at' => null, // free plan doesn't expire
                ]);
            }
        }
    }
}
