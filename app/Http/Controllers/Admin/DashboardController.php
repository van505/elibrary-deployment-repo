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
use App\Models\Setting;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

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

        // ── Widget visibility settings ──────────────────────────────────────
        $widgets = [
            'metrics'               => filter_var(Setting::where('key', 'dashboard.widget.metrics')->value('value')               ?? 'true',  FILTER_VALIDATE_BOOLEAN),
            'subscriptions_chart'   => filter_var(Setting::where('key', 'dashboard.widget.subscriptions_chart')->value('value')   ?? 'true',  FILTER_VALIDATE_BOOLEAN),
            'recent_transactions'   => filter_var(Setting::where('key', 'dashboard.widget.recent_transactions')->value('value')   ?? 'true',  FILTER_VALIDATE_BOOLEAN),
            'action_required'       => filter_var(Setting::where('key', 'dashboard.widget.action_required')->value('value')       ?? 'true',  FILTER_VALIDATE_BOOLEAN),
            'most_read_ebooks'      => filter_var(Setting::where('key', 'dashboard.widget.most_read_ebooks')->value('value')      ?? 'true',  FILTER_VALIDATE_BOOLEAN),
            'recent_members'        => filter_var(Setting::where('key', 'dashboard.widget.recent_members')->value('value')        ?? 'false', FILTER_VALIDATE_BOOLEAN),
            'activity_feed'         => filter_var(Setting::where('key', 'dashboard.widget.activity_feed')->value('value')         ?? 'false', FILTER_VALIDATE_BOOLEAN),
        ];

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
            'subscriptionChart',
            'widgets'
        ));
    }

    public function saveWidgets(Request $request)
    {
        $allowed = [
            'metrics', 'subscriptions_chart', 'recent_transactions',
            'action_required', 'most_read_ebooks', 'recent_members', 'activity_feed',
        ];

        foreach ($request->input('widgets', []) as $key => $value) {
            if (!in_array($key, $allowed)) continue;
            Setting::updateOrCreate(
                ['key'   => 'dashboard.widget.' . $key],
                ['value' => $value ? 'true' : 'false']
            );
        }

        return response()->json(['success' => true]);
    }
}
