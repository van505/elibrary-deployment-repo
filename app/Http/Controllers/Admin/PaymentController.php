<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Member;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('member.user', 'borrowing')->paginate(10);

        return view('admin.payments.index', compact('payments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id'      => 'required|exists:members,id',
            'borrowing_id'   => 'required|exists:borrowings,id',
            'amount'         => 'required|numeric|min:0',
            'payment_type'   => 'required|in:fine,membership',
            'payment_status' => 'required|in:pending,paid,waived',
            'reference_no'   => 'nullable|string|max:255',
        ]);

        if ($validated['payment_status'] === 'paid') {
            $validated['paid_at'] = now();
        }

        $payment = Payment::create($validated);

        ActivityLogger::log('created', 'payments', 'Recorded payment ID: ' . $payment->id . ' amount: ' . $payment->amount);

        return redirect()->route('admin.payments.index')->with('success', 'Payment recorded successfully.');
    }

    public function show($id)
    {
        $payment = Payment::with('member.user', 'borrowing.ebook')->findOrFail($id);

        return view('admin.payments.show', compact('payment'));
    }
}
