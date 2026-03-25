@extends('layouts.admin')
@section('title', 'Subscription Detail')

@section('content')
<div class="max-w-2xl space-y-5">
    <a href="{{ route('admin.subscriptions.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-blue-600">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Subscriptions
    </a>

    <div class="grid grid-cols-2 gap-5">
        {{-- Member Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Member</h2>
            <p class="font-bold text-gray-800">{{ $subscription->member?->full_name ?: 'N/A' }}</p>
            <p class="text-sm text-gray-500">{{ $subscription->member?->user?->email ?? '' }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $subscription->member?->member_code ?? '' }}</p>
        </div>

        {{-- Plan Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Plan</h2>
            <p class="font-bold text-gray-800">{{ $subscription->plan->name ?? 'N/A' }}</p>
            <p class="text-sm text-gray-500">₱{{ number_format($subscription->plan->price ?? 0, 2) }}/month</p>
            <p class="text-xs text-gray-400 mt-1">Limit: {{ $subscription->plan->ebook_limit === -1 ? 'Unlimited' : $subscription->plan->ebook_limit }}</p>
        </div>
    </div>

    {{-- Subscription Info --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Subscription Info</h2>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div><span class="text-gray-400">Status:</span>
                @php $statusColors = ['active'=>'bg-green-100 text-green-700','expired'=>'bg-red-100 text-red-700','cancelled'=>'bg-gray-100 text-gray-600']; @endphp
                <span class="ml-2 text-xs font-semibold px-2 py-0.5 rounded-full {{ $statusColors[$subscription->status] ?? '' }}">{{ ucfirst($subscription->status) }}</span>
            </div>
            <div><span class="text-gray-400">Started:</span> <span class="text-gray-700 ml-2">{{ $subscription->started_at?->format('M d, Y') ?? '—' }}</span></div>
            <div><span class="text-gray-400">Expires:</span> <span class="text-gray-700 ml-2">{{ $subscription->expires_at?->format('M d, Y') ?? 'Never' }}</span></div>
        </div>
    </div>

    {{-- Transaction History --}}
    @if($transactions->count())
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100"><h2 class="text-sm font-semibold text-gray-700">Transaction History</h2></div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500"><tr>
                <th class="text-left px-5 py-2">Reference</th>
                <th class="text-left px-5 py-2">Amount</th>
                <th class="text-left px-5 py-2">Method</th>
                <th class="text-left px-5 py-2">Status</th>
                <th class="text-left px-5 py-2">Date</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($transactions as $txn)
                <tr>
                    <td class="px-5 py-3 font-mono text-xs text-gray-600">{{ $txn->reference_no ?? '—' }}</td>
                    <td class="px-5 py-3 font-medium">₱{{ number_format($txn->amount, 2) }}</td>
                    <td class="px-5 py-3 text-gray-600 capitalize">{{ str_replace('_', ' ', $txn->payment_method) }}</td>
                    <td class="px-5 py-3">
                        @php $c=['completed'=>'text-green-600','pending'=>'text-yellow-600','failed'=>'text-red-600']; @endphp
                        <span class="text-xs font-semibold {{ $c[$txn->status] ?? '' }}">{{ ucfirst($txn->status) }}</span>
                    </td>
                    <td class="px-5 py-3 text-gray-500">{{ $txn->paid_at?->format('M d, Y') ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection
