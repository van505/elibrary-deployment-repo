<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Author;
use App\Models\Category;
use App\Models\Ebook;
use App\Models\EbookAccess;
use App\Models\Member;
use App\Models\Review;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // ── Stat Card Data ─────────────────────────────────────────────────────
        $totalUsers          = User::count();
        $totalEbooks         = Ebook::count();
        $totalMembers        = Member::count();
        $totalAuthors        = Author::count();
        $totalCategories     = Category::count();
        $activeSubscriptions = Subscription::where('status', 'active')->count();

        // Revenue this month (completed transactions)
        $revenueThisMonth = Transaction::where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        // Total revenue all-time
        $totalRevenue = Transaction::where('status', 'completed')->sum('amount');

        // Pending reviews needing approval
        $pendingReviews = Review::where('status', 'pending')->count();

        // New members this month
        $newMembersThisMonth = Member::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Premium members
        $premiumMembers = Subscription::where('status', 'active')
            ->whereHas('plan', fn ($q) => $q->where('slug', 'premium'))
            ->count();

        // Announcements
        $activeAnnouncements = \App\Models\Announcement::active()->get();

        // ── Full Widget Data ───────────────────────────────────────────────────

        // Activity Feed widget
        $activityFeed = ActivityLog::with('user')
            ->latest('created_at')
            ->take(8)
            ->get();

        // Recent Transactions widget
        $recentTransactions = Transaction::with(['member', 'plan'])
            ->latest()
            ->take(6)
            ->get();

        // Top Ebooks widget (by number of accesses)
        $topEbooks = Ebook::withCount('ebookAccess')
            ->with('authors')
            ->orderByDesc('ebook_access_count')
            ->take(5)
            ->get();

        // Subscription chart — monthly counts for the last 6 months
        $subscriptionChart = collect(range(5, 0))->map(function ($monthsAgo) {
            $date = now()->subMonths($monthsAgo);
            return [
                'label' => $date->format('M'),
                'count' => Subscription::whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->count(),
            ];
        });

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalEbooks',
            'totalMembers',
            'totalAuthors',
            'totalCategories',
            'activeSubscriptions',
            'revenueThisMonth',
            'totalRevenue',
            'pendingReviews',
            'newMembersThisMonth',
            'premiumMembers',
            'activeAnnouncements',
            'activityFeed',
            'recentTransactions',
            'topEbooks',
            'subscriptionChart'
        ));
    }
}
