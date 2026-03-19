@extends('layouts.admin')
@section('title', 'Member Details')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <a href="{{ route('admin.members.index') }}" class="text-blue-600 hover:underline text-sm">← Back</a>
    <a href="{{ route('admin.members.edit', $member) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm">Edit</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-bold text-gray-800 mb-4">Member Info</h3>
        <dl class="space-y-3 text-sm">
            <div><dt class="text-gray-500">Name</dt><dd class="font-medium">{{ $member->user->name }}</dd></div>
            <div><dt class="text-gray-500">Email</dt><dd>{{ $member->user->email }}</dd></div>
            <div><dt class="text-gray-500">Code</dt><dd class="font-mono text-xs">{{ $member->member_code }}</dd></div>
            <div><dt class="text-gray-500">Phone</dt><dd>{{ $member->phone ?? '—' }}</dd></div>
            <div><dt class="text-gray-500">Status</dt><dd><span class="px-2 py-0.5 rounded text-xs {{ $member->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ $member->status }}</span></dd></div>
            <div><dt class="text-gray-500">Expiry</dt><dd>{{ $member->membership_expiry?->format('M d, Y') ?? '—' }}</dd></div>
            <div><dt class="text-gray-500">Address</dt><dd>{{ $member->address ?? '—' }}</dd></div>
        </dl>
    </div>
    <div class="lg:col-span-2 space-y-6">
        {{-- Borrowings --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 font-semibold text-gray-800">Borrowing History</div>
            @forelse($member->borrowings as $b)
            <div class="px-6 py-3 border-t border-gray-100 flex justify-between text-sm">
                <span>{{ $b->ebook->title ?? 'N/A' }}</span>
                <span class="text-gray-400">{{ $b->borrow_date?->format('M d, Y') }}</span>
                <span class="px-2 py-0.5 rounded text-xs {{ $b->status === 'returned' ? 'bg-green-100 text-green-700' : ($b->status === 'overdue' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700') }}">{{ $b->status }}</span>
            </div>
            @empty
            <div class="px-6 py-4 text-center text-gray-400 text-sm">No borrowings.</div>
            @endforelse
        </div>
        {{-- Payments --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 font-semibold text-gray-800">Payments</div>
            @forelse($member->payments as $p)
            <div class="px-6 py-3 border-t border-gray-100 flex justify-between text-sm">
                <span>{{ $p->payment_type }}</span>
                <span class="font-medium">₱{{ number_format($p->amount, 2) }}</span>
                <span class="px-2 py-0.5 rounded text-xs {{ $p->payment_status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">{{ $p->payment_status }}</span>
            </div>
            @empty
            <div class="px-6 py-4 text-center text-gray-400 text-sm">No payments.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
