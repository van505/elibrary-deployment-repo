<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Ebook;
use App\Models\Member;
use App\Models\Payment;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers      = User::count();
        $totalEbooks     = Ebook::count();
        $activeBorrowings = Borrowing::where('status', 'active')->count();
        $overdueBooks    = Borrowing::where('status', 'overdue')->count();
        $totalRevenue    = Payment::where('payment_status', 'paid')->sum('amount');
        $totalMembers    = Member::count();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalEbooks',
            'activeBorrowings',
            'overdueBooks',
            'totalRevenue',
            'totalMembers'
        ));
    }
}
