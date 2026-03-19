@extends('layouts.member')
@section('title', $ebook->title)

@section('content')
<div class="mb-6"><a href="{{ route('member.ebooks.index') }}" class="text-blue-600 hover:underline text-sm">← Back to Browse</a></div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    {{-- Cover --}}
    <div class="flex flex-col items-center gap-4">
        <img src="{{ $ebook->cover_image ? Storage::url($ebook->cover_image) : '/images/placeholder.png' }}"
             class="w-full max-w-xs rounded-xl shadow" onerror="this.src='/images/placeholder.png'">
        <div class="flex flex-col gap-2 w-full">
            <form action="{{ route('member.borrowings.store') }}" method="POST">
                @csrf
                <input type="hidden" name="ebook_id" value="{{ $ebook->id }}">
                <button type="submit" {{ $ebook->available_copies < 1 ? 'disabled' : '' }}
                        class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 text-white px-4 py-2 rounded-lg text-sm">
                    {{ $ebook->available_copies > 0 ? 'Borrow Now' : 'Unavailable' }}
                </button>
            </form>
            <form action="{{ route('member.reservations.store') }}" method="POST">
                @csrf
                <input type="hidden" name="ebook_id" value="{{ $ebook->id }}">
                <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm">Reserve</button>
            </form>
        </div>
    </div>

    {{-- Details --}}
    <div class="lg:col-span-3 space-y-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <span class="bg-blue-100 text-blue-700 text-xs px-2 py-0.5 rounded-full mb-2 inline-block">{{ $ebook->category->name }}</span>
            <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ $ebook->title }}</h1>
            <p class="text-gray-500 mb-4">by <span class="font-medium text-gray-700">{{ $ebook->author->name }}</span></p>
            <dl class="grid grid-cols-2 gap-3 text-sm">
                <div><dt class="text-gray-400">Publisher</dt><dd>{{ $ebook->publisher ?? '—' }}</dd></div>
                <div><dt class="text-gray-400">Year</dt><dd>{{ $ebook->publish_year ?? '—' }}</dd></div>
                <div><dt class="text-gray-400">Available</dt><dd class="{{ $ebook->available_copies > 0 ? 'text-green-600' : 'text-red-500' }} font-medium">{{ $ebook->available_copies }}/{{ $ebook->total_copies }}</dd></div>
                <div><dt class="text-gray-400">ISBN</dt><dd>{{ $ebook->isbn ?? '—' }}</dd></div>
            </dl>
        </div>

        {{-- Reviews --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-semibold text-gray-800">Reviews ({{ $approvedReviews->count() }})</h3>
                @php $avg = $approvedReviews->avg('rating'); @endphp
                @if($approvedReviews->count() > 0)
                <span class="text-yellow-500 font-medium">{{ str_repeat('★', round($avg)) }} {{ number_format($avg, 1) }}</span>
                @endif
            </div>

            @forelse($approvedReviews as $review)
            <div class="px-6 py-4 border-t border-gray-100">
                <div class="flex justify-between items-start mb-1">
                    <p class="font-medium text-sm text-gray-800">{{ $review->member->user->name ?? 'Member' }}</p>
                    <span class="text-yellow-500">{{ str_repeat('★', $review->rating) }}<span class="text-gray-200">{{ str_repeat('★', 5 - $review->rating) }}</span></span>
                </div>
                @if($review->comment)<p class="text-sm text-gray-600">{{ $review->comment }}</p>@endif
            </div>
            @empty
            <div class="px-6 py-6 text-center text-gray-400 text-sm">No reviews yet.</div>
            @endforelse

            {{-- Add Review --}}
            @if($hasBorrowed)
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                <h4 class="font-medium text-gray-700 mb-3 text-sm">Add Your Review</h4>
                <form action="{{ route('member.reviews.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="ebook_id" value="{{ $ebook->id }}">
                    <div class="mb-3">
                        <label class="block text-sm text-gray-600 mb-1">Rating</label>
                        <select name="rating" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @for($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}">{{ str_repeat('★', $i) }} {{ $i }}/5</option>
                            @endfor
                        </select>
                    </div>
                    <div class="mb-3">
                        <textarea name="comment" rows="3" placeholder="Write your review…" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">Submit Review</button>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
