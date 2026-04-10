<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Traits\HandlesAdminFilters;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    use HandlesAdminFilters;

    public function index(Request $request)
    {
        $query = Transaction::with(['member.user', 'plan']);
        $query = $this->applyFilters(
            $query, 
            $request, 
            'filter_transactions', 
            ['transaction_id', 'member.full_name'], // searchable
            ['status', 'payment_method'], // filterable
            ['created_at'] // date ranges
        );

        $transactions = $query->paginate(15)->appends($request->query());
        return view('admin.transactions.index', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load('member.user', 'plan');
        return view('admin.transactions.show', compact('transaction'));
    }
}
