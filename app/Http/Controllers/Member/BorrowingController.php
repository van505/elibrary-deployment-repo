<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Ebook;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    public function index()
    {
        $member    = auth()->user()->member;
        $borrowings = $member->borrowings()->with('ebook')->paginate(10);

        return view('member.borrowings.index', compact('borrowings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ebook_id' => 'required|exists:ebooks,id',
        ]);

        $member = auth()->user()->member;
        $ebook  = Ebook::findOrFail($request->ebook_id);

        // Check active borrowing limit
        $max     = (int) Setting::get('max_active_borrowings', 3);
        $current = $member->borrowings()->where('status', 'active')->count();

        if ($current >= $max) {
            return redirect()->back()->with('error', 'You have reached the maximum borrowing limit.');
        }

        // Check ebook availability
        if ($ebook->available_copies < 1) {
            return redirect()->back()->with('error', 'This ebook is currently not available.');
        }

        $maxDays = (int) Setting::get('max_borrow_days', 7);

        Borrowing::create([
            'member_id'   => $member->id,
            'ebook_id'    => $ebook->id,
            'borrow_date' => Carbon::today(),
            'due_date'    => Carbon::today()->addDays($maxDays),
            'status'      => 'active',
            'fine_amount' => 0,
        ]);

        $ebook->decrement('available_copies');

        return redirect()->route('member.borrowings.index')->with('success', 'Ebook borrowed successfully.');
    }
}
