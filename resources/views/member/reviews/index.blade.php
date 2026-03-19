@extends('layouts.member')
@section('title', 'My Reviews')

@section('content')
<h2 class="text-2xl font-bold text-gray-800 mb-6">My Reviews</h2>

@php $member = auth()->user()->member; $reviews = $member ? $member->reviews()->with('ebook')->latest()->paginate(10) : collect(); @endphp

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
            <tr>
                <th class="px-6 py-3">Ebook</th>
                <th class="px-6 py-3">Rating</th>
                <th class="px-6 py-3">Comment</th>
                <th class="px-6 py-3">Status</th>
                <th class="px-6 py-3">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reviews as $r)
            <tr class="border-t border-gray-100 hover:bg-gray-50">
                <td class="px-6 py-3 font-medium text-gray-800">{{ $r->ebook->title }}</td>
                <td class="px-6 py-3">
                    <span class="text-yellow-500">{{ str_repeat('★', $r->rating) }}</span><span class="text-gray-300">{{ str_repeat('★', 5 - $r->rating) }}</span>
                </td>
                <td class="px-6 py-3 text-gray-500 max-w-xs truncate">{{ $r->comment ?? '—' }}</td>
                <td class="px-6 py-3">
                    @php $sc = ['pending'=>'bg-yellow-100 text-yellow-700','approved'=>'bg-green-100 text-green-700','rejected'=>'bg-red-100 text-red-700']; @endphp
                    <span class="px-2 py-0.5 rounded text-xs font-medium {{ $sc[$r->status] ?? '' }}">{{ $r->status }}</span>
                </td>
                <td class="px-6 py-3">
                    <form action="{{ route('member.reviews.destroy', $r) }}" method="POST" onsubmit="return confirm('Delete this review?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400">You haven't submitted any reviews yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100">{{ $reviews->links() }}</div>
</div>
@endsection
