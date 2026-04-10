<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EbookAccess;
use App\Models\Member;
use App\Models\Review;
use App\Models\Transaction;
use App\Models\Category;
use App\Traits\HandlesAdminFilters;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    use HandlesAdminFilters;

    public function index(Request $request)
    {
        // Use a dummy query just to trigger the trait's session tracking and Clear logic
        $dummyQuery = Member::query();
        $this->applyFilters($dummyQuery, $request, 'filter_reports', [], ['category_id'], ['report_date']);

        $startDate = $request->filled('report_date_start') ? Carbon::parse($request->get('report_date_start'))->startOfDay() : now()->startOfMonth();
        $endDate = $request->filled('report_date_end') ? Carbon::parse($request->get('report_date_end'))->endOfDay() : now()->endOfDay();
        $categoryId = $request->get('category_id');

        $categories = Category::orderBy('name')->get();

        // Total accesses within range
        $accessesThisMonth = EbookAccess::whereBetween('created_at', [$startDate, $endDate])
            ->when($categoryId, fn($q, $cat) => $q->whereHas('ebook', fn($eq) => $eq->where('category_id', $cat)))
            ->count();

        // Most read ebooks within range
        $topEbooks = EbookAccess::selectRaw('ebook_id, count(*) as access_count')
            ->with('ebook')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->when($categoryId, fn($q, $cat) => $q->whereHas('ebook', fn($eq) => $eq->where('category_id', $cat)))
            ->groupBy('ebook_id')
            ->orderByDesc('access_count')
            ->limit(5)
            ->get();

        // Most active members within range
        $topMembers = EbookAccess::selectRaw('member_id, count(*) as access_count')
            ->with('member')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->when($categoryId, fn($q, $cat) => $q->whereHas('ebook', fn($eq) => $eq->where('category_id', $cat)))
            ->groupBy('member_id')
            ->orderByDesc('access_count')
            ->limit(5)
            ->get();

        // Review approval rate within range
        $reviewQuery = Review::whereBetween('created_at', [$startDate, $endDate])
            ->when($categoryId, fn($q, $cat) => $q->whereHas('ebook', fn($eq) => $eq->where('category_id', $cat)));
            
        $totalReviews    = (clone $reviewQuery)->count();
        $approvedReviews = (clone $reviewQuery)->where('status', 'approved')->count();
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
            'memberData',
            'categories'
        ));
    }
}
