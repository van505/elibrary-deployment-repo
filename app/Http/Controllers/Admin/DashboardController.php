<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ebook;
use App\Models\Member;
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
        $activeSubscriptions = Subscription::where('status', 'active')->count();
        $totalRevenue        = Transaction::where('status', 'completed')->sum('amount');
        $premiumMembers      = Subscription::where('status', 'active')
                                ->whereHas('plan', fn ($q) => $q->where('slug', 'premium'))
                                ->count();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalEbooks',
            'totalMembers',
            'activeSubscriptions',
            'totalRevenue',
            'premiumMembers'
        ));
    }
}
