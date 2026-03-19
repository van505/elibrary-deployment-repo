<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Ebook;
use App\Models\Member;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    public function index()
    {
        $status = request('status', 'all');

        $query = Borrowing::with('member.user', 'ebook');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $borrowings = $query->paginate(10);

        return view('admin.borrowings.index', compact('borrowings', 'status'));
    }

    public function create()
    {
        $members = Member::where('status', 'active')->get();
        $ebooks  = Ebook::where('available_copies', '>', 0)->get();

        return view('admin.borrowings.create', compact('members', 'ebooks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id'   => 'required|exists:members,id',
            'ebook_id'    => 'required|exists:ebooks,id',
            'borrow_date' => 'required|date',
            'due_date'    => 'required|date|after_or_equal:borrow_date',
        ]);

        $validated['status']      = 'active';
        $validated['fine_amount'] = 0;

        $borrowing = Borrowing::create($validated);

        Ebook::where('id', $validated['ebook_id'])->decrement('available_copies');

        ActivityLogger::log('created', 'borrowings', 'Created borrowing for ebook ID: ' . $validated['ebook_id']);

        return redirect()->route('admin.borrowings.index')->with('success', 'Borrowing record created successfully.');
    }

    public function show($id)
    {
        $borrowing = Borrowing::with('member.user', 'ebook')->findOrFail($id);

        return view('admin.borrowings.show', compact('borrowing'));
    }

    public function returnBook($id)
    {
        $borrowing = Borrowing::with('ebook')->findOrFail($id);

        $returnDate = Carbon::today();
        $dueDate    = Carbon::parse($borrowing->due_date);

        $borrowing->return_date = $returnDate;
        $borrowing->status      = 'returned';

        if ($returnDate->gt($dueDate)) {
            $overdueDays           = $dueDate->diffInDays($returnDate);
            $rate                  = (float) Setting::get('fine_rate_per_day', 5.00);
            $borrowing->fine_amount = $overdueDays * $rate;
        }

        $borrowing->save();

        Ebook::where('id', $borrowing->ebook_id)->increment('available_copies');

        ActivityLogger::log('returned', 'borrowings', 'Returned ebook ID: ' . $borrowing->ebook_id);

        return redirect()->route('admin.borrowings.index')->with('success', 'Book returned successfully.');
    }
}
