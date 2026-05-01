@extends('layouts.admin')
@section('title', 'Transactions')

@push('breadcrumbs')
<nav class="flex items-center text-sm" aria-label="Breadcrumb">
    <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-gray-600 transition-colors duration-150">Dashboard</a>
    <span class="text-gray-300 mx-1.5 select-none">›</span>
    <span class="text-gray-400 mx-1.5">Billing</span>
    <span class="text-gray-300 mx-1.5 select-none">›</span>
    <span class="text-gray-700 font-medium">Transactions</span>
</nav>
@endpush

@section('content')
<div class="space-y-5">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Transactions</h1>
            <p class="text-gray-500 text-sm mt-1">Review payment history and transaction records</p>
        </div>
    </div>
    <x-admin.filter-bar :action="route('admin.transactions.index')" searchPlaceholder="Search ID or member..." :sortable="['created_at' => 'Date Processed', 'amount' => 'Amount']">
        <input type="date" name="created_at_start" value="{{ request('created_at_start') }}" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none" title="Start Date">
        <input type="date" name="created_at_end"   value="{{ request('created_at_end') }}"   class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none" title="End Date">
        <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            <option value="">All Statuses</option>
            <option value="completed" @selected(request('status') === 'completed')>Completed</option>
            <option value="pending"   @selected(request('status') === 'pending')>Pending</option>
            <option value="failed"    @selected(request('status') === 'failed')>Failed</option>
        </select>
        <select name="payment_method" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            <option value="">All Methods</option>
            <option value="credit_card"   @selected(request('payment_method') === 'credit_card')>Credit Card</option>
            <option value="paypal"        @selected(request('payment_method') === 'paypal')>PayPal</option>
            <option value="gcash"         @selected(request('payment_method') === 'gcash')>GCash</option>
            <option value="maya"          @selected(request('payment_method') === 'maya')>Maya</option>
            <option value="bank_transfer" @selected(request('payment_method') === 'bank_transfer')>Bank Transfer</option>
        </select>
    </x-admin.filter-bar>

    <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Plan</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($transactions as $txn)
                @php
                    $memberName = $txn->member?->full_name ?: ($txn->member?->user?->email ?? 'N/A');
                    $initial    = strtoupper(substr($memberName, 0, 1));
                    $planName   = $txn->plan->name ?? '';
                    $isFree    = stripos($planName, 'free') !== false;
                    $isPremium = stripos($planName, 'premium') !== false;
                    $tColors = [
                        'completed' => ['pill' => 'bg-green-50 text-green-700',   'dot' => 'bg-green-500'],
                        'pending'   => ['pill' => 'bg-yellow-50 text-yellow-700', 'dot' => 'bg-yellow-400'],
                        'failed'    => ['pill' => 'bg-red-50 text-red-600',       'dot' => 'bg-red-400'],
                    ];
                    // Payment method icon path
                    $isCard = in_array($txn->payment_method, ['credit_card', 'debit_card']);
                @endphp
                <tr class="hover:bg-gray-50/60 transition-colors duration-200">
                    {{-- Member Avatar --}}
                    <td class="px-4 py-4">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center text-xs font-bold flex-shrink-0">{{ $initial }}</div>
                            <p class="text-sm font-bold text-gray-900">{{ $memberName }}</p>
                        </div>
                    </td>
                    {{-- Plan + Tier Icon --}}
                    <td class="px-4 py-4">
                        <div class="flex items-center gap-2">
                            @if($isFree)
                                <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            @elseif($isPremium)
                                <svg class="w-4 h-4 text-amber-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a1 1 0 00-1.832.514L1.077 11H3a1 1 0 01.832.445l1.5 2.25 2.25-4.5a1 1 0 011.789.051l1.5 3 1.118-2.236A1 1 0 0113 10h2.5L13.832 4.514A1 1 0 0012 4a1 1 0 00-.832.445L10 7.118 8.832 4.445A1 1 0 008 4H5z"/></svg>
                            @else
                                <svg class="w-4 h-4 text-blue-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endif
                            <span class="font-medium text-gray-700">{{ $planName ?: 'N/A' }}</span>
                        </div>
                    </td>
                    {{-- Amount --}}
                    <td class="px-4 py-4">
                        <span class="text-gray-500 text-xs">₱</span><span class="font-semibold text-gray-900">{{ number_format($txn->amount, 2) }}</span>
                    </td>
                    {{-- Method + icon --}}
                    <td class="px-4 py-4">
                        <div class="flex items-center gap-1.5">
                            @if($isCard)
                                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            @else
                                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            @endif
                            <span class="text-sm text-gray-700 capitalize">{{ str_replace('_', ' ', $txn->payment_method) }}</span>
                        </div>
                    </td>
                    {{-- Status --}}
                    <td class="px-4 py-4">
                        <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-0.5 rounded-full {{ $tColors[$txn->status]['pill'] ?? 'bg-gray-100 text-gray-500' }}">
                            <span class="w-1.5 h-1.5 rounded-full inline-block {{ $tColors[$txn->status]['dot'] ?? 'bg-gray-400' }}"></span>
                            {{ ucfirst($txn->status) }}
                        </span>
                    </td>
                    {{-- Reference --}}
                    <td class="px-4 py-4 font-mono text-xs text-gray-500">{{ $txn->reference_no ?? '—' }}</td>
                    {{-- Date --}}
                    <td class="px-4 py-4 text-xs text-gray-500 whitespace-nowrap">{{ $txn->paid_at?->format('M d, Y') ?? '—' }}</td>
                    {{-- Eye icon --}}
                    <td class="px-4 py-4">
                        <a href="{{ route('admin.transactions.show', $txn) }}"
                           class="p-1.5 text-blue-500 hover:text-blue-700 rounded-lg transition-colors duration-200 inline-flex" title="View">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-6 py-10 text-center text-gray-400">No transactions found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100">{{ $transactions->links() }}</div>
    </div>
</div>
@endsection
