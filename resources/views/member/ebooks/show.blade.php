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
                <p class="text-gray-500 mt-1">by {{ $ebook->authors->pluck('name')->join(', ') ?: 'Unknown Author' }}</p>
            </div>

            <div class="flex flex-wrap gap-3 text-sm text-gray-600">
                @if($ebook->publisher) <span>📖 {{ $ebook->publisher }}</span> @endif
                @if($ebook->publish_year) <span>📅 {{ $ebook->publish_year }}</span> @endif
                <span>📄 {{ strtoupper($ebook->file_type) }}</span>
                @if($ebook->isbn) <span>ISBN: {{ $ebook->isbn }}</span> @endif
            </div>

            <div class="flex gap-3 pt-2">
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
            </div>
        </div>
    </div>

    {{-- Reviews --}}
    @if($approvedReviews->count())
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="font-semibold text-gray-800 mb-4">Reader Reviews ({{ $approvedReviews->count() }})</h2>
        <div class="space-y-4">
            @foreach($approvedReviews as $review)
            <div class="border-b border-gray-100 pb-4 last:border-0 last:pb-0">
                <div class="flex items-center gap-2 mb-1">
                    <span class="font-medium text-sm text-gray-700">{{ $review->member->user->name ?? 'Member' }}</span>
                    <span class="text-yellow-400 text-sm">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span>
                </div>
                @if($review->comment)
                    <p class="text-sm text-gray-600">{{ $review->comment }}</p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection
