@extends('layouts.admin')
@section('title', 'Transaction Detail')

@section('content')
<div class="max-w-xl space-y-5">
    <a href="{{ route('admin.transactions.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-blue-600">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Transactions
    </a>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-5">
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-xl font-bold text-gray-800">Transaction Detail</h1>
                <p class="font-mono text-sm text-gray-400 mt-0.5">{{ $transaction->reference_no ?? 'N/A' }}</p>
            </div>
            @php $colors=['completed'=>'bg-green-100 text-green-700','pending'=>'bg-yellow-100 text-yellow-700','failed'=>'bg-red-100 text-red-700']; @endphp
            <span class="text-sm font-semibold px-3 py-1 rounded-full {{ $colors[$transaction->status] ?? 'bg-gray-100 text-gray-600' }}">{{ ucfirst($transaction->status) }}</span>
        </div>

        <div class="grid grid-cols-2 gap-y-4 text-sm">
            <div>
                <p class="text-gray-400 text-xs uppercase tracking-wide">Member</p>
                <p class="font-semibold text-gray-800 mt-0.5">{{ $transaction->member->user->name ?? 'N/A' }}</p>
                <p class="text-gray-500 text-xs">{{ $transaction->member->user->email ?? '' }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs uppercase tracking-wide">Plan</p>
                <p class="font-semibold text-gray-800 mt-0.5">{{ $transaction->plan->name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs uppercase tracking-wide">Amount</p>
                <p class="text-2xl font-bold text-gray-900 mt-0.5">₱{{ number_format($transaction->amount, 2) }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs uppercase tracking-wide">Payment Method</p>
                <p class="font-semibold text-gray-800 mt-0.5 capitalize">{{ str_replace('_', ' ', $transaction->payment_method) }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs uppercase tracking-wide">Paid At</p>
                <p class="font-semibold text-gray-800 mt-0.5">{{ $transaction->paid_at?->format('M d, Y h:i A') ?? 'Not yet paid' }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs uppercase tracking-wide">Created At</p>
                <p class="font-semibold text-gray-800 mt-0.5">{{ $transaction->created_at->format('M d, Y') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
