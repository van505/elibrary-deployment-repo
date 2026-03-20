<?php

namespace App\Observers;

use App\Models\Member;
use App\Models\User;
use Illuminate\Support\Str;

class UserObserver
{
    /**
     * Auto-create a Member record when a new member-role user registers.
     */
    public function created(User $user): void
    {
        if ($user->role === 'member') {
            Member::create([
                'user_id'           => $user->id,
                'member_code'       => 'MBR-' . strtoupper(Str::random(6)),
                'phone'             => null,
                'address'           => null,
                'status'            => 'active',
                'membership_expiry' => now()->addDays(365),
            ]);
        }
    }
}
