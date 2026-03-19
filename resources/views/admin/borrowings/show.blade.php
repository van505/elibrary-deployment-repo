@extends('layouts.admin')
@section('title', 'Borrowing Details')

@section('content')
<div class="mb-6"><a href="{{ route('admin.borrowings.index') }}" class="text-blue-600 hover:underline text-sm">← Back</a></div>

<div class="bg-white rounded-xl shadow-sm p-6 max-w-2xl">
    <h2 class="text-xl font-bold text-gray-800 mb-6">Borrowing #{{ $borrowing->id }}</h2>
    <dl class="grid grid-cols-2 gap-4 text-sm mb-6">
        <div><dt class="text-gray-500">Member</dt><dd class="font-medium">{{ $borrowing->member->user->name }}</dd></div>
        <div><dt class="text-gray-500">Ebook</dt><dd class="font-medium">{{ $borrowing->ebook->title }}</dd></div>
        <div><dt class="text-gray-500">Borrow Date</dt><dd>{{ $borrowing->borrow_date?->format('M d, Y') }}</dd></div>
        <div><dt class="text-gray-500">Due Date</dt><dd>{{ $borrowing->due_date?->format('M d, Y') }}</dd></div>
        <div><dt class="text-gray-500">Return Date</dt><dd>{{ $borrowing->return_date?->format('M d, Y') ?? '—' }}</dd></div>
        <div><dt class="text-gray-500">Status</dt>
            <dd>
                @php $bc = ['active'=>'bg-blue-100 text-blue-700','returned'=>'bg-green-100 text-green-700','overdue'=>'bg-red-100 text-red-700']; @endphp
                <span class="px-2 py-0.5 rounded text-xs font-medium {{ $bc[$borrowing->status] ?? '' }}">{{ $borrowing->status }}</span>
            </dd>
        </div>
        <div><dt class="text-gray-500">Fine Amount</dt><dd>{{ $borrowing->fine_amount > 0 ? '₱'.number_format($borrowing->fine_amount,2) : 'None' }}</dd></div>
    </dl>

    @if($borrowing->status === 'active')
    <form action="{{ route('admin.borrowings.return', $borrowing->id) }}" method="POST" onsubmit="return confirm('Mark as returned?')">
        @csrf
        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm">Mark as Returned</button>
    </form>
    @endif
</div>
@endsection
