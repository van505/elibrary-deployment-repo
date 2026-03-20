<?php

namespace App\Http\Controllers\Member;

class DashboardController extends BaseMemberController
{
    public function index()
    {
        $member = $this->getOrCreateMember();

        $subscription = $member->activeSubscription();
        $plan         = $member->currentPlan();
        $accessCount  = $member->ebookAccess()->count();
        $recentAccess = $member->ebookAccess()
            ->with('ebook.authors')
            ->latest()
            ->take(5)
            ->get();

        // Calculate days left — 0 if expired, null if no expiry (unlimited)
        $daysLeft = null;
        if ($subscription && $subscription->expires_at) {
            $daysLeft = (int) max(0, now()->diffInDays($subscription->expires_at, false));
        }

        return view('member.dashboard', compact(
            'member',
            'subscription',
            'plan',
            'accessCount',
            'recentAccess',
            'daysLeft'
        ));
    }
}
