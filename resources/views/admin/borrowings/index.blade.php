@extends('layouts.admin')
@section('title', 'Borrowings')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Borrowings</h2>
    <a href="{{ route('admin.borrowings.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">+ New Borrowing</a>
</div>

{{-- Filter Tabs --}}
<div class="flex gap-2 mb-4">
    @foreach(['all','active','returned','overdue'] as $tab)
    <a href="{{ route('admin.borrowings.index', ['status' => $tab]) }}"
       class="px-4 py-2 rounded-lg text-sm font-medium {{ $status === $tab ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-100 shadow-sm' }}">
        {{ ucfirst($tab) }}
    </a>
    @endforeach
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
            <tr>
                <th class="px-6 py-3">Member</th>
                <th class="px-6 py-3">Ebook</th>
                <th class="px-6 py-3">Borrow Date</th>
                <th class="px-6 py-3">Due Date</th>
                <th class="px-6 py-3">Return Date</th>
                <th class="px-6 py-3">Status</th>
                <th class="px-6 py-3">Fine</th>
                <th class="px-6 py-3">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($borrowings as $b)
            <tr class="border-t border-gray-100 hover:bg-gray-50">
                <td class="px-6 py-3">{{ $b->member->user->name }}</td>
                <td class="px-6 py-3 max-w-xs truncate">{{ $b->ebook->title }}</td>
                <td class="px-6 py-3 text-gray-500">{{ $b->borrow_date?->format('M d, Y') }}</td>
                <td class="px-6 py-3 text-gray-500">{{ $b->due_date?->format('M d, Y') }}</td>
                <td class="px-6 py-3 text-gray-500">{{ $b->return_date?->format('M d, Y') ?? '—' }}</td>
                <td class="px-6 py-3">
                    @php $bc = ['active'=>'bg-blue-100 text-blue-700','returned'=>'bg-green-100 text-green-700','overdue'=>'bg-red-100 text-red-700']; @endphp
                    <span class="px-2 py-0.5 rounded text-xs font-medium {{ $bc[$b->status] ?? '' }}">{{ $b->status }}</span>
                </td>
                <td class="px-6 py-3">{{ $b->fine_amount > 0 ? '₱'.number_format($b->fine_amount,2) : '—' }}</td>
                <td class="px-6 py-3 flex gap-2">
                    <a href="{{ route('admin.borrowings.show', $b) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">View</a>
                    @if($b->status === 'active')
                    <form action="{{ route('admin.borrowings.return', $b->id) }}" method="POST" onsubmit="return confirm('Mark as returned?')">
                        @csrf
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">Return</button>
                    </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="px-6 py-8 text-center text-gray-400">No borrowings found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100">{{ $borrowings->links() }}</div>
</div>
@endsection
