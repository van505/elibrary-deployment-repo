<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $user   = auth()->user();
        $member = $user->member;

        if (! $member) {
            return redirect()->route('member.ebooks.index');
        }

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
