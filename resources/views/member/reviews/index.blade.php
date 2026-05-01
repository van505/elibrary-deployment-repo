@extends('layouts.member')
@section('title', 'My Reviews')

@push('breadcrumbs')
<nav class="flex items-center text-sm">
    <a href="{{ route('member.dashboard') }}" class="text-gray-400 hover:text-gray-600 transition-colors duration-150">Home</a>
    <span class="text-gray-300 mx-1.5 select-none">›</span>
    <span class="text-gray-400 mx-1.5">My Account</span>
    <span class="text-gray-300 mx-1.5 select-none">›</span>
    <span class="text-gray-700 font-medium">My Reviews</span>
</nav>
@endpush

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">My Reviews</h2>
        <p class="text-sm text-gray-500 mt-1">All reviews you've submitted</p>
    </div>

    @if($reviews->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-16 text-center text-gray-400">
            <svg class="w-16 h-16 mx-auto mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
            </svg>
            <p class="font-medium">You haven't submitted any reviews yet.</p>
            <a href="{{ route('member.ebooks.index') }}" class="mt-3 inline-block text-blue-600 hover:underline text-sm">Browse Ebooks →</a>
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3">Ebook</th>
                        <th class="px-6 py-3">Rating</th>
                        <th class="px-6 py-3">Comment</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Date</th>
                        <th class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reviews as $review)
                    <tr class="border-t border-gray-100 hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($review->ebook->cover_image)
                                    <img src="{{ asset('storage/' . $review->ebook->cover_image) }}" alt="" class="w-10 h-14 object-cover rounded">
                                @else
                                    <div class="w-10 h-14 bg-gradient-to-br from-blue-100 to-blue-200 rounded flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13"/></svg>
                                    </div>
                                @endif
                                <div>
                                    <a href="{{ route('member.ebooks.show', $review->ebook->id) }}" class="font-medium text-gray-800 hover:text-blue-600 transition-colors line-clamp-2">
                                        {{ $review->ebook->title }}
                                    </a>
                                    <p class="text-xs text-gray-400">{{ $review->ebook->authors->pluck('full_name')->join(', ') ?: 'Unknown Author' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-0.5">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="{{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-200' }} text-base">★</span>
                                @endfor
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-500 max-w-xs">
                            {{ $review->comment ? \Illuminate\Support\Str::limit($review->comment, 80) : '—' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($review->status === 'pending')
                                <span class="bg-yellow-100 text-yellow-700 text-xs font-semibold px-2.5 py-1 rounded-full">⏳ Awaiting Approval</span>
                            @elseif($review->status === 'approved')
                                <span class="bg-green-100 text-green-700 text-xs font-semibold px-2.5 py-1 rounded-full">✓ Published</span>
                            @else
                                <span class="bg-red-100 text-red-700 text-xs font-semibold px-2.5 py-1 rounded-full">✕ Rejected</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-400 whitespace-nowrap">{{ $review->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4">
                            <form action="{{ route('member.reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Delete this review?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="px-6 py-4 border-t border-gray-100">{{ $reviews->links() }}</div>
        </div>
    @endif
</div>
@endsection
