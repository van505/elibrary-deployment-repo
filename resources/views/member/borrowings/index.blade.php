@extends('layouts.member')
@section('title', 'My Borrowings')

@section('content')
<h2 class="text-2xl font-bold text-gray-800 mb-6">My Borrowings</h2>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
            <tr>
                <th class="px-6 py-3">Ebook</th>
                <th class="px-6 py-3">Borrow Date</th>
                <th class="px-6 py-3">Due Date</th>
                <th class="px-6 py-3">Return Date</th>
                <th class="px-6 py-3">Status</th>
                <th class="px-6 py-3">Fine</th>
            </tr>
        </thead>
        <tbody>
            @forelse($borrowings as $b)
            <tr class="border-t border-gray-100 hover:bg-gray-50 {{ $b->status === 'overdue' ? 'bg-red-50' : '' }}">
                <td class="px-6 py-3 font-medium text-gray-800">{{ $b->ebook->title }}</td>
                <td class="px-6 py-3 text-gray-500">{{ $b->borrow_date?->format('M d, Y') }}</td>
                <td class="px-6 py-3 {{ $b->status === 'overdue' ? 'text-red-600 font-medium' : 'text-gray-500' }}">{{ $b->due_date?->format('M d, Y') }}</td>
                <td class="px-6 py-3 text-gray-500">{{ $b->return_date?->format('M d, Y') ?? '—' }}</td>
                <td class="px-6 py-3">
                    @php $bc = ['active'=>'bg-blue-100 text-blue-700','returned'=>'bg-green-100 text-green-700','overdue'=>'bg-red-100 text-red-700']; @endphp
                    <span class="px-2 py-0.5 rounded text-xs font-medium {{ $bc[$b->status] ?? '' }}">{{ $b->status }}</span>
                </td>
                <td class="px-6 py-3 text-gray-500">{{ $b->fine_amount > 0 ? '₱'.number_format($b->fine_amount,2) : '—' }}</td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-6 py-8 text-center text-gray-400">No borrowings yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100">{{ $borrowings->links() }}</div>
</div>
@endsection
