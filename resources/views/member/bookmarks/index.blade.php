@extends('layouts.member')
@section('title', 'My Bookmarks')

@push('breadcrumbs')
<nav class="flex items-center text-sm">
    <a href="{{ route('member.dashboard') }}" class="text-gray-400 hover:text-gray-600 transition-colors duration-150">Home</a>
    <span class="text-gray-300 mx-1.5 select-none">›</span>
    <span class="text-gray-400 mx-1.5">My Account</span>
    <span class="text-gray-300 mx-1.5 select-none">›</span>
    <span class="text-gray-700 font-medium">My Bookmarks</span>
</nav>
@endpush

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">My Bookmarks</h2>
        <p class="text-sm text-gray-500 mt-1">Ebooks you've saved for later</p>
    </div>

    @if($bookmarks->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-16 text-center text-gray-400">
            <svg class="w-16 h-16 mx-auto mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
            </svg>
            <p class="font-medium">No bookmarks yet.</p>
            <a href="{{ route('member.ebooks.index') }}" class="mt-3 inline-block text-blue-600 hover:underline text-sm">Browse Ebooks →</a>
        </div>
    @else
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
            @foreach($bookmarks as $bookmark)
            @php $ebook = $bookmark->ebook; @endphp
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow group">
                <div class="aspect-[3/4] bg-gray-100 overflow-hidden">
                    <a href="{{ route('member.ebooks.show', $ebook->id) }}" class="block w-full h-full">
                        @if($ebook->cover_image)
                            <img src="{{ asset('storage/' . $ebook->cover_image) }}" alt="{{ $ebook->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-50 to-blue-100">
                                <svg class="w-12 h-12 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13"/></svg>
                            </div>
                        @endif
                    </a>
                </div>
                <div class="p-3">
                    <h3 class="font-semibold text-gray-800 text-sm truncate mb-0.5">
                        <a href="{{ route('member.ebooks.show', $ebook->id) }}" class="hover:text-blue-600 transition-colors">{{ $ebook->title }}</a>
                    </h3>
                    <p class="text-xs text-gray-400 truncate mb-3">{{ $ebook->authors->pluck('full_name')->join(', ') ?: 'Unknown Author' }}</p>
                    <div class="flex gap-2">
                        <a href="{{ route('member.ebooks.show', $ebook->id) }}"
                           class="flex-1 text-center bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium py-1.5 rounded-lg transition-colors">
                            View
                        </a>
                        <form action="{{ route('member.bookmarks.toggle', $ebook->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-2 py-1.5 bg-yellow-100 hover:bg-yellow-200 text-yellow-700 rounded-lg text-sm transition-colors" title="Remove bookmark">
                                ★
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div>{{ $bookmarks->links() }}</div>
    @endif
</div>
@endsection
