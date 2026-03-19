@extends('layouts.admin')
@section('title', 'Payments')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Payments</h2>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
            <tr>
                <th class="px-6 py-3">Member</th>
                <th class="px-6 py-3">Amount</th>
                <th class="px-6 py-3">Type</th>
                <th class="px-6 py-3">Status</th>
                <th class="px-6 py-3">Reference</th>
                <th class="px-6 py-3">Paid At</th>
                <th class="px-6 py-3">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $p)
            <tr class="border-t border-gray-100 hover:bg-gray-50">
                <td class="px-6 py-3">{{ $p->member->user->name }}</td>
                <td class="px-6 py-3 font-medium">₱{{ number_format($p->amount, 2) }}</td>
                <td class="px-6 py-3 text-gray-500">{{ $p->payment_type }}</td>
                <td class="px-6 py-3">
                    @php $pc = ['paid'=>'bg-green-100 text-green-700','pending'=>'bg-yellow-100 text-yellow-700','waived'=>'bg-gray-100 text-gray-500']; @endphp
                    <span class="px-2 py-0.5 rounded text-xs font-medium {{ $pc[$p->payment_status] ?? '' }}">{{ $p->payment_status }}</span>
                </td>
                <td class="px-6 py-3 text-gray-500">{{ $p->reference_no ?? '—' }}</td>
                <td class="px-6 py-3 text-gray-500">{{ $p->paid_at?->format('M d, Y') ?? '—' }}</td>
                <td class="px-6 py-3">
                    <a href="{{ route('admin.payments.show', $p) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">View</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-6 py-8 text-center text-gray-400">No payments found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100">{{ $payments->links() }}</div>
</div>
@endsection
