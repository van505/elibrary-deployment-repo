@extends('layouts.member')
@section('title', $ebook->title)

@section('content')
<div class="space-y-6">

    {{-- Back --}}
    <a href="{{ route('member.ebooks.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-blue-600 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Ebooks
    </a>

    <div class="grid grid-cols-1 md:grid-cols-[200px_1fr] gap-8">

        {{-- Cover --}}
        <div>
            <div class="aspect-[3/4] bg-gray-100 rounded-xl overflow-hidden mb-4">
                @if($ebook->cover_image)
                    <img src="{{ asset('storage/' . $ebook->cover_image) }}" alt="{{ $ebook->title }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-50 to-blue-100">
                        <svg class="w-16 h-16 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13"/></svg>
                    </div>
                @endif
            </div>
        </div>

        {{-- Info --}}
        <div class="space-y-4">
            <div>
                @php
                    $levelColors = ['free'=>'bg-green-100 text-green-700','basic'=>'bg-blue-100 text-blue-700','premium'=>'bg-purple-100 text-purple-700'];
                @endphp
                <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $levelColors[$ebook->access_level] ?? 'bg-gray-100 text-gray-600' }}">
                    {{ ucfirst($ebook->access_level) }} Plan Required
                </span>
                <h1 class="text-3xl font-bold text-gray-800 mt-2">{{ $ebook->title }}</h1>
                <p class="text-gray-500 mt-1">by {{ $ebook->authors->pluck('full_name')->join(', ') ?: 'Unknown Author' }}</p>
            </div>

            <div class="flex flex-wrap gap-3 text-sm text-gray-600">
                @if($ebook->publisher) <span>📖 {{ $ebook->publisher }}</span> @endif
                @if($ebook->publish_year) <span>📅 {{ $ebook->publish_year }}</span> @endif
                <span>📄 {{ strtoupper($ebook->file_type) }}</span>
                @if($ebook->isbn) <span>ISBN: {{ $ebook->isbn }}</span> @endif
            </div>

            @if($ebook->tags->isNotEmpty())
            <div class="flex flex-wrap gap-2 pt-1">
                @foreach($ebook->tags as $tag)
                    <a href="{{ route('member.ebooks.index', ['tag' => $tag->tag_name]) }}" 
                       class="bg-gray-100 hover:bg-gray-200 transition-colors text-gray-600 px-3 py-1 text-xs rounded-full border border-gray-200">
                        #{{ $tag->tag_name }}
                    </a>
                @endforeach
            </div>
            @endif

            <div class="flex flex-wrap gap-3 pt-2">
                @if($hasAccess)
                    <a href="{{ route('member.ebooks.read', $ebook->id) }}"
                       class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-lg text-sm font-medium transition-colors">
                        Read Now
                    </a>
                    <form action="{{ route('member.ebooks.remove-access', $ebook->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="border border-red-300 text-red-600 hover:bg-red-50 px-6 py-2.5 rounded-lg text-sm font-medium transition-colors"
                                onclick="return confirm('Remove from reading list?')">
                            Remove from Reading List
                        </button>
                    </form>
                @else
                    <form action="{{ route('member.ebooks.access', $ebook->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg text-sm font-medium transition-colors">
                            Read Now
                        </button>
                    </form>
                    <a href="{{ route('member.subscriptions.index') }}" class="border border-gray-300 text-gray-600 hover:bg-gray-50 px-6 py-2.5 rounded-lg text-sm font-medium transition-colors">
                        View Plans
                    </a>
                @endif

                {{-- Bookmarks --}}
                <form action="{{ route('member.bookmarks.toggle', $ebook->id) }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="px-4 py-2.5 rounded-lg text-sm font-medium transition-colors border shadow-sm
                                   {{ $isBookmarked
                                       ? 'bg-yellow-50 border-yellow-300 text-yellow-700 hover:bg-yellow-100'
                                       : 'border-gray-200 bg-white text-gray-700 hover:bg-gray-50' }}">
                        {{ $isBookmarked ? '★ Bookmarked' : '☆ Save to Bookmarks' }}
                    </button>
                </form>

                {{-- Wishlist --}}
                <form action="{{ route('member.wishlist.toggle', $ebook->id) }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="px-4 py-2.5 rounded-lg text-sm font-medium transition-colors border shadow-sm flex items-center gap-2
                                   {{ isset($isWishlisted) && $isWishlisted
                                       ? 'bg-purple-50 border-purple-300 text-purple-700 hover:bg-purple-100'
                                       : 'border-gray-200 bg-white text-gray-700 hover:bg-gray-50' }}">
                        @if(isset($isWishlisted) && $isWishlisted)
                            <span>✅ In Wishlist</span>
                        @else
                            <span>📋 Add to Wishlist</span>
                        @endif
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Reviews Section --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-5">
            Reviews
            @if($reviews->count()) <span class="text-gray-400 font-normal text-sm">({{ $reviews->count() }})</span> @endif
        </h2>

        {{-- Member's own PENDING reviews (only visible to that member) --}}
        @if($pendingReviews->count())
        <div class="mb-5 space-y-3">
            @foreach($pendingReviews as $pr)
            <div class="border border-yellow-200 bg-yellow-50 rounded-lg p-4">
                <div class="flex items-center justify-between mb-1">
                    <div class="flex items-center gap-2">
                        <span class="font-medium text-sm text-gray-700">Your Review</span>
                        <span class="bg-yellow-200 text-yellow-800 text-xs font-semibold px-2 py-0.5 rounded-full">⏳ Awaiting Approval</span>
                    </div>
                    <div class="flex items-center gap-0.5">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="{{ $i <= $pr->rating ? 'text-yellow-400' : 'text-gray-200' }} text-base">★</span>
                        @endfor
                    </div>
                </div>
                @if($pr->comment)
                    <p class="text-sm text-gray-600">{{ $pr->comment }}</p>
                @endif
                <p class="text-xs text-gray-400 mt-1">Submitted {{ $pr->created_at->diffForHumans() }}</p>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Approved reviews list --}}
        @forelse($reviews as $review)
        <div class="border-b border-gray-100 pb-4 mb-4 last:border-0 last:mb-0 last:pb-0">
            <div class="flex items-center justify-between mb-1">
                <span class="font-medium text-sm text-gray-700">{{ $review->member->full_name ?: ($review->member->user->email ?? 'Member') }}</span>
                <div class="flex items-center gap-0.5">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="{{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-200' }} text-base">★</span>
                    @endfor
                </div>
            </div>
            @if($review->comment)
                <p class="text-sm text-gray-600">{{ $review->comment }}</p>
            @endif
        </div>
        @empty
            @if(!$pendingReviews->count())
                <p class="text-gray-400 text-sm">No reviews yet. Be the first to review!</p>
            @endif
        @endforelse

        {{-- Write a Review form — always shown when member has access --}}
        @if($hasAccess)
        <div class="mt-6 pt-6 border-t border-gray-100">
            <h3 class="font-semibold text-gray-800 mb-4">Write a Review</h3>
            <form method="POST" action="{{ route('member.reviews.store') }}" id="review-form">
                @csrf
                <input type="hidden" name="ebook_id" value="{{ $ebook->id }}">

                {{-- Star Rating --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rating <span class="text-red-500">*</span></label>
                    <div class="flex gap-2" id="star-container">
                        @for($i = 1; $i <= 5; $i++)
                        <label class="cursor-pointer">
                            <input type="radio" name="rating" value="{{ $i }}" class="sr-only" required>
                            <span class="text-4xl text-gray-300 hover:text-yellow-400 transition-colors" data-star="{{ $i }}">★</span>
                        </label>
                        @endfor
                    </div>
                    @error('rating') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Comment --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Comment <span class="text-gray-400 text-xs">(optional)</span></label>
                    <textarea name="comment" rows="4"
                              placeholder="Share your thoughts about this ebook..."
                              class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none resize-none">{{ old('comment') }}</textarea>
                    @error('comment') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg text-sm font-medium transition-colors">
                    Submit Review
                </button>
                <p class="text-xs text-gray-400 mt-2">All reviews are subject to admin approval before being published.</p>
            </form>
        </div>

        <script>
        // Interactive star rating
        const container = document.getElementById('star-container');
        const stars = container.querySelectorAll('[data-star]');
        const inputs = container.querySelectorAll('input[type="radio"]');

        stars.forEach((star, idx) => {
            star.addEventListener('mouseover', () => highlightStars(idx + 1));
            star.addEventListener('click', () => {
                inputs[idx].checked = true;
                highlightStars(idx + 1, true);
            });
        });

        container.addEventListener('mouseleave', () => {
            const checked = [...inputs].findIndex(i => i.checked);
            checked >= 0 ? highlightStars(checked + 1, true) : highlightStars(0);
        });

        function highlightStars(count, persist = false) {
            stars.forEach((star, i) => {
                star.classList.toggle('text-yellow-400', i < count);
                star.classList.toggle('text-gray-300', i >= count);
            });
        }
        </script>

        @elseif(!$hasAccess)
        <div class="mt-6 pt-6 border-t border-gray-100 bg-gray-50 rounded-lg p-4">
            <p class="text-gray-500 text-sm">📖 Access this ebook first to leave a review.</p>
        </div>
        @endif
    </div>

    @if($relatedEbooks->isNotEmpty())
    <div class="pt-8 border-t border-gray-100">
        <h2 class="text-xl font-bold text-gray-800 mb-6">You Might Also Like</h2>
        
        {{-- Horizontal scroll on mobile, grid on desktop --}}
        <div class="flex overflow-x-auto md:grid md:grid-cols-4 gap-4 pb-4 md:pb-0 snap-x">
            @foreach($relatedEbooks as $related)
            <div class="bg-white border text-center border-gray-100 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow group shrink-0 w-[180px] md:w-auto snap-center flex flex-col">
                {{-- Cover Image --}}
                <div class="aspect-[3/4] bg-gray-50 relative overflow-hidden flex-none">
                    @if($related->cover_image)
                        <img src="{{ asset('storage/' . $related->cover_image) }}" alt="{{ $related->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-50 to-blue-100 group-hover:scale-105 transition-transform duration-300">
                            <svg class="w-12 h-12 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                    @endif
                    
                    {{-- Access Badge Overlay --}}
                    @php
                        $levelColors = ['free'=>'bg-green-500','basic'=>'bg-blue-500','premium'=>'bg-purple-500'];
                    @endphp
                    <div class="absolute top-2 right-2">
                        <span class="text-[10px] font-bold text-white px-2 py-0.5 rounded-full {{ $levelColors[$related->access_level] ?? 'bg-gray-500' }} shadow-sm">
                            {{ strtoupper($related->access_level) }}
                        </span>
                    </div>
                </div>

                {{-- Content Info --}}
                <div class="p-4 flex flex-col flex-1 justify-between gap-3">
                    <div>
                        <h3 class="font-bold text-gray-800 text-sm line-clamp-2 leading-tight" title="{{ $related->title }}">{{ $related->title }}</h3>
                        <p class="text-xs text-gray-500 mt-1 line-clamp-1" title="{{ $related->authors->pluck('full_name')->join(', ') }}">{{ $related->authors->pluck('full_name')->join(', ') ?: 'Unknown Author' }}</p>
                    </div>
                    <a href="{{ route('member.ebooks.show', $related) }}" class="block w-full py-2 bg-gray-50 text-blue-600 hover:bg-blue-50 hover:text-blue-700 text-xs font-semibold rounded-lg transition-colors border border-gray-100">
                        View Book
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection
