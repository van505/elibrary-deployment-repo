<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $member = auth()->user()->member;

        $activeBorrowings   = $member->borrowings()->where('status', 'active')->count();
        $overdueBorrowings  = $member->borrowings()->where('status', 'overdue')->get();
        $reservations       = $member->reservations()->where('status', 'pending')->count();

        return view('member.dashboard', compact(
            'member',
            'activeBorrowings',
            'overdueBorrowings',
            'reservations'
        ));
    }
}
