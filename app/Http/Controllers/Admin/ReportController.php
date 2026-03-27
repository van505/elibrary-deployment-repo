<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EbookAccess;
use App\Models\Member;
use App\Models\Review;
use App\Models\Transaction;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        // Total ebooks accessed this month
        $accessesThisMonth = EbookAccess::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Most read ebooks — top 5
        $topEbooks = EbookAccess::selectRaw('ebook_id, count(*) as access_count')
            ->with('ebook')
            ->groupBy('ebook_id')
            ->orderByDesc('access_count')
            ->limit(5)
            ->get();

        // Most active members — top 5
        $topMembers = EbookAccess::selectRaw('member_id, count(*) as access_count')
            ->with('member')
            ->groupBy('member_id')
            ->orderByDesc('access_count')
            ->limit(5)
            ->get();

        // Review approval rate
        $totalReviews    = Review::count();
        $approvedReviews = Review::where('status', 'approved')->count();
        $approvalRate    = $totalReviews > 0 ? round(($approvedReviews / $totalReviews) * 100, 1) : 0;

        // Revenue last 6 months (bar chart)
        $revenueData = [];
        $revenueLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $revenueLabels[] = $month->format('M Y');
            $revenueData[] = Transaction::where('status', 'completed')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('amount');
        }

        // Member growth last 6 months (line chart)
        $memberData = [];
        $memberLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $memberLabels[] = $month->format('M Y');
            $memberData[] = Member::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }

        return view('admin.reports.index', compact(
            'accessesThisMonth',
            'topEbooks',
            'topMembers',
            'approvalRate',
            'totalReviews',
            'approvedReviews',
            'revenueLabels',
            'revenueData',
            'memberLabels',
            'memberData'
        ));
    }
}
