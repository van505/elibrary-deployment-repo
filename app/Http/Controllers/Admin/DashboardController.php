<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Category;
use App\Models\Ebook;
use App\Models\Member;
use App\Models\Review;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
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
            'premiumMembers'
        ));
    }
}
