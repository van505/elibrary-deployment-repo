@extends('layouts.admin')
@section('title', 'Transactions')

@section('content')
<div class="space-y-5">
    <h1 class="text-xl font-bold text-gray-800">Transactions</h1>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left font-semibold text-gray-600 px-6 py-3">Member</th>
                    <th class="text-left font-semibold text-gray-600 px-6 py-3">Plan</th>
                    <th class="text-left font-semibold text-gray-600 px-6 py-3">Amount</th>
                    <th class="text-left font-semibold text-gray-600 px-6 py-3">Method</th>
                    <th class="text-left font-semibold text-gray-600 px-6 py-3">Status</th>
                    <th class="text-left font-semibold text-gray-600 px-6 py-3">Reference</th>
                    <th class="text-left font-semibold text-gray-600 px-6 py-3">Date</th>
                    <th class="text-left font-semibold text-gray-600 px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($transactions as $txn)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <p class="font-medium text-gray-800">{{ $txn->member->user->name ?? 'N/A' }}</p>
                    </td>
                    <td class="px-6 py-4 text-gray-700">{{ $txn->plan->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4 font-semibold text-gray-800">₱{{ number_format($txn->amount, 2) }}</td>
                    <td class="px-6 py-4 text-gray-600 capitalize">{{ str_replace('_', ' ', $txn->payment_method) }}</td>
                    <td class="px-6 py-4">
                        @php $colors=['completed'=>'bg-green-100 text-green-700','pending'=>'bg-yellow-100 text-yellow-700','failed'=>'bg-red-100 text-red-700']; @endphp
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $colors[$txn->status] ?? 'bg-gray-100 text-gray-600' }}">{{ ucfirst($txn->status) }}</span>
                    </td>
                    <td class="px-6 py-4 font-mono text-xs text-gray-500">{{ $txn->reference_no ?? '—' }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $txn->paid_at?->format('M d, Y') ?? '—' }}</td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.transactions.show', $txn) }}" class="text-blue-600 hover:underline text-xs">View</a>
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
