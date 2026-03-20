<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        $member = auth()->user()->member;

        // Null-safety: create a Member record on-the-fly for users that
        // registered before the UserObserver was in place.
        if (! $member) {
            $member = Member::create([
                'user_id'           => auth()->id(),
                'member_code'       => 'MBR-' . strtoupper(Str::random(6)),
                'phone'             => null,
                'address'           => null,
                'status'            => 'active',
                'membership_expiry' => now()->addDays(365),
            ]);
        }

        $activeBorrowings  = $member->borrowings()->where('status', 'active')->count();
        $overdueBorrowings = $member->borrowings()->where('status', 'overdue')->get();
        $reservations      = $member->reservations()->where('status', 'pending')->count();

        return view('member.dashboard', compact(
            'member',
            'activeBorrowings',
            'overdueBorrowings',
            'reservations'
        ));
    }
}
