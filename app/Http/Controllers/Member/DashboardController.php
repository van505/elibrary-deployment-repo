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

        return view('member.dashboard', compact(
            'member',
            'subscription',
            'plan',
            'accessCount',
            'recentAccess'
        ));
    }
}
