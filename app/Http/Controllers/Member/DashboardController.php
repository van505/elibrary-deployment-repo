<?php

namespace App\Http\Controllers\Member;

use App\Models\EbookAccess;
use App\Models\Review;

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

        // Ebooks accessed this month
        $ebooksThisMonth = $member->ebookAccess()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Total reviews submitted
        $reviewsCount = Review::where('member_id', $member->id)->count();

        // Calculate days left — 0 if expired, null if no expiry (unlimited)
        $daysLeft = null;
        $daysTotal = null;
        if ($subscription && $subscription->expires_at) {
            $daysLeft = (int) max(0, now()->diffInDays($subscription->expires_at, false));
            if ($subscription->started_at) {
                $daysTotal = (int) $subscription->started_at->diffInDays($subscription->expires_at);
            }
        }

        // Editor's Choice spotlight
        $spotlightEbook = \App\Models\Ebook::with('authors')->where('is_spotlighted', true)->first();

        // Wishlist items
        $wishlistItems = $member->wishlistedEbooks()->with('authors', 'category')->latest('ebook_wishlists.created_at')->take(4)->get();

        // Featured collections 
        $featuredCollections = \App\Models\Collection::active()->withCount('ebooks')->latest()->take(3)->get();

        // Recommended Ebooks — based on preferences or newest
        $hasPreferences = !empty($member->preferred_categories);
        if ($hasPreferences) {
            $recommendedEbooks = \App\Models\Ebook::with(['authors', 'category'])
                ->whereIn('category_id', $member->preferred_categories)
                ->latest()
                ->take(6)
                ->get();
        } else {
            $recommendedEbooks = \App\Models\Ebook::with(['authors', 'category'])
                ->latest()
                ->take(6)
                ->get();
        }

        return view('member.dashboard', compact(
            'member',
            'subscription',
            'plan',
            'accessCount',
            'recentAccess',
            'daysLeft',
            'daysTotal',
            'ebooksThisMonth',
            'reviewsCount',
            'spotlightEbook',
            'wishlistItems',
            'featuredCollections',
            'recommendedEbooks',
            'hasPreferences'
        ));
    }
}
