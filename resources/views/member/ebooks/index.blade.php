@extends('layouts.member')
@section('title', 'Browse Ebooks')

@push('breadcrumbs')
<nav class="flex items-center text-sm">
    <a href="{{ route('member.dashboard') }}" class="text-gray-400 hover:text-gray-600 transition-colors duration-150">Home</a>
    <span class="text-gray-300 mx-1.5 select-none">›</span>
    <span class="text-gray-400 mx-1.5">Library</span>
    <span class="text-gray-300 mx-1.5 select-none">›</span>
    <span class="text-gray-700 font-medium">Browse Ebooks</span>
</nav>
@endpush

@section('content')
<div class="space-y-6">

    {{-- Search & Filters --}}
    <form method="GET" class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 mb-6">
        <div class="flex flex-wrap gap-3">
            <div class="relative flex-1 min-w-48">
                <input type="text" id="member-ebook-search" name="search" value="{{ request('search') }}" autocomplete="off"
                       placeholder="Search by title or author..."
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                <x-search-autocomplete-js input-id="member-ebook-search" />
            </div>
            <select name="category_id" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>{{ $cat->name }}</option>
                @endforeach
            </select>
            <select name="access_level" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                <option value="">All Levels</option>
                <option value="free"    @selected(request('access_level') === 'free')>Free</option>
                <option value="basic"   @selected(request('access_level') === 'basic')>Basic</option>
                <option value="premium" @selected(request('access_level') === 'premium')>Premium</option>
            </select>
            
            {{-- Sort By Dropdown --}}
            <select name="sort" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                <option value="">Sort By: Newest</option>
                <option value="popular" @selected(request('sort') === 'popular')>Most Popular</option>
                <option value="az" @selected(request('sort') === 'az')>Title (A-Z)</option>
            </select>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-medium transition-colors">Search</button>
            
            @if(request()->hasAny(['search','category_id','access_level','tag','sort']))
                <a href="{{ route('member.ebooks.index') }}" class="text-gray-500 hover:text-gray-700 px-4 py-2 text-sm flex items-center">Clear</a>
            @endif

            {{-- Grid/List View Toggles --}}
            <div class="ml-auto flex items-center border border-gray-200 rounded-lg p-1 bg-gray-50">
                <button type="submit" name="view" value="grid" class="p-1.5 rounded-md transition-colors {{ request('view', 'grid') !== 'list' ? 'bg-indigo-50 text-indigo-600 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}" title="Grid View">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                </button>
                <button type="submit" name="view" value="list" class="p-1.5 rounded-md transition-colors {{ request('view') === 'list' ? 'bg-indigo-50 text-indigo-600 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}" title="List View">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>
                </button>
            </div>

            @if(request('tag'))
                <input type="hidden" name="tag" value="{{ request('tag') }}">
                <span class="flex items-center gap-1 bg-blue-50 text-blue-700 px-3 py-2 rounded-lg text-sm border border-blue-200">
                    Tag: <strong class="font-medium">{{ request('tag') }}</strong>
                </span>
            @endif
        </div>
    </form>

    {{-- Ebooks Grid --}}
    @if($ebooks->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-16 text-center text-gray-400">
            <svg class="w-16 h-16 mx-auto mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13"/></svg>
            <p class="font-medium">No ebooks found.</p>
        </div>
    @else
        @php $isListView = request('view') === 'list'; @endphp
        <div class="{{ $isListView ? 'flex flex-col gap-4' : 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6' }}">
            @foreach($ebooks as $ebook)
            @php
                $accessed = in_array($ebook->id, $accessedIds ?? []);
                $inWishlist = in_array($ebook->id, $wishlistIds ?? []);
                $levelColors = ['free' => 'bg-green-100 text-green-700', 'basic' => 'bg-blue-100 text-blue-700', 'premium' => 'bg-purple-100 text-purple-700'];
                $levelColor = $levelColors[$ebook->access_level] ?? 'bg-gray-100 text-gray-600';
            @endphp
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md transition-shadow group flex {{ $isListView ? 'flex-col sm:flex-row h-auto sm:h-56' : 'flex-col h-full' }}">
                {{-- The "Museum" Cover Container --}}
                <div class="relative bg-gray-50 flex items-center justify-center p-2 flex-shrink-0 group/cover {{ $isListView ? 'w-full sm:w-44 h-64 sm:h-full border-b sm:border-b-0 sm:border-r border-gray-100' : 'w-full h-72' }}">
                    <a href="{{ route('member.ebooks.show', $ebook->id) }}" class="block w-full h-full flex items-center justify-center">
                        @if($ebook->cover_image)
                            <img src="{{ asset('storage/' . $ebook->cover_image) }}" alt="{{ $ebook->title }}" class="max-w-full max-h-full object-contain drop-shadow-md group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-50 to-blue-100 shadow-sm rounded-md">
                                <svg class="w-12 h-12 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13"/></svg>
                            </div>
                        @endif
                    </a>

                    {{-- Wishlist Hover Action --}}
                    <form action="{{ route('member.wishlist.toggle', $ebook->id) }}" method="POST" class="absolute top-2 right-2 {{ $inWishlist ? 'opacity-100' : 'opacity-0 group-hover/cover:opacity-100' }} transition-opacity duration-200 z-10">
                        @csrf
                        <button type="submit" class="bg-white/90 backdrop-blur-sm p-1.5 rounded-full hover:bg-white transition-all focus:outline-none {{ $inWishlist ? 'text-red-500 hover:text-red-600 shadow-md scale-110' : 'shadow-sm text-gray-400 hover:text-red-500' }}" title="{{ $inWishlist ? 'Remove from Wishlist' : 'Add to Wishlist' }}">
                            <svg class="w-5 h-5 {{ $inWishlist ? 'fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        </button>
                    </form>

                    {{-- Access Level Badge --}}
                    <span class="absolute top-2 left-2 text-xs font-semibold px-2 py-0.5 rounded-full shadow-sm {{ $levelColor }} pointer-events-none">
                        {{ ucfirst($ebook->access_level) }}
                    </span>
                    
                    {{-- Reading Badge --}}
                    @if($accessed)
                        <span class="absolute bottom-2 right-2 bg-blue-600 text-white text-xs font-semibold px-2 py-0.5 rounded-full shadow-sm pointer-events-none">Reading</span>
                    @elseif(($ebook->preview_pages ?? 0) > 0)
                        <span class="absolute bottom-2 right-2 bg-amber-400 text-gray-900 text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm pointer-events-none">👁 Preview</span>
                    @endif
                </div>

                <div class="p-5 flex flex-col flex-grow">
                    <h3 class="font-semibold text-gray-800 text-sm mb-1 line-clamp-2">
                        <a href="{{ route('member.ebooks.show', $ebook->id) }}" class="hover:text-blue-600 transition-colors">
                            {{ $ebook->title }}
                        </a>
                    </h3>
                    <p class="text-xs text-gray-500 truncate mb-2">
                        {{ $ebook->authors->pluck('full_name')->join(', ') ?: 'Unknown Author' }}
                    </p>
                    
                    @if($isListView)
                        <p class="text-sm text-gray-600 line-clamp-2 mt-2 mb-3">
                            {{ $ebook->description ?? 'No description available for this ebook.' }}
                        </p>
                    @endif

                    @if($ebook->tags->isNotEmpty())
                    <div class="flex flex-wrap gap-1 mb-3 max-h-12 overflow-hidden relative">
                        @foreach($ebook->tags as $tag)
                            <a href="{{ route('member.ebooks.index', array_merge(request()->query(), ['tag' => $tag->tag_name])) }}" 
                               class="bg-gray-100 hover:bg-gray-200 text-gray-500 hover:text-gray-700 text-[10px] px-2 py-0.5 rounded transition-colors whitespace-nowrap">
                                {{ $tag->tag_name }}
                            </a>
                        @endforeach
                    </div>
                    @else
                    <div class="mb-3 h-5"></div>
                    @endif

                    @if($accessed)
                        <div class="mt-auto pt-4 {{ $isListView ? 'w-full sm:w-48 max-w-full' : '' }}">
                            <a href="{{ route('member.ebooks.read', $ebook->id) }}"
                               class="block text-center bg-green-600 hover:bg-green-700 text-white text-xs font-medium py-2 rounded-lg transition-colors">
                                Continue Reading
                            </a>
                        </div>
                    @else
                        <div class="mt-auto pt-4 {{ $isListView ? 'w-full sm:w-48 max-w-full' : '' }}">
                            <form action="{{ route('member.ebooks.access', $ebook->id) }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium py-2 rounded-lg transition-colors">
                                    Read Now
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <div>{{ $ebooks->withQueryString()->links() }}</div>
    @endif

</div>
@endsection
