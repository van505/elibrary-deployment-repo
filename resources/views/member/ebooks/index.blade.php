@extends('layouts.member')
@section('title', 'Browse Ebooks')

@section('content')
<div class="space-y-6">

    {{-- Search & Filters --}}
    <form method="GET" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
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
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-medium transition-colors">Search</button>
            @if(request()->hasAny(['search','category_id','access_level','tag']))
                <a href="{{ route('member.ebooks.index') }}" class="text-gray-500 hover:text-gray-700 px-4 py-2 text-sm">Clear</a>
            @endif
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
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
            @foreach($ebooks as $ebook)
            @php
                $accessed = in_array($ebook->id, $accessedIds ?? []);
                $levelColors = ['free' => 'bg-green-100 text-green-700', 'basic' => 'bg-blue-100 text-blue-700', 'premium' => 'bg-purple-100 text-purple-700'];
                $levelColor = $levelColors[$ebook->access_level] ?? 'bg-gray-100 text-gray-600';
            @endphp
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow group">
                {{-- Cover --}}
                <div class="aspect-[3/4] bg-gray-100 overflow-hidden relative">
                    <a href="{{ route('member.ebooks.show', $ebook->id) }}" class="block w-full h-full">
                        @if($ebook->cover_image)
                            <img src="{{ asset('storage/' . $ebook->cover_image) }}" alt="{{ $ebook->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-50 to-blue-100">
                                <svg class="w-12 h-12 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13"/></svg>
                            </div>
                        @endif
                    </a>
                    {{-- Access Level Badge --}}
                    <span class="absolute top-2 left-2 text-xs font-semibold px-2 py-0.5 rounded-full {{ $levelColor }}">
                        {{ ucfirst($ebook->access_level) }}
                    </span>
                    {{-- Reading Badge --}}
                    @if($accessed)
                        <span class="absolute top-2 right-2 bg-blue-600 text-white text-xs font-semibold px-2 py-0.5 rounded-full">Reading</span>
                    @elseif(($ebook->preview_pages ?? 0) > 0)
                        <span class="absolute top-2 right-2 bg-amber-400 text-gray-900 text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm">👁 Preview</span>
                    @endif
                </div>

                <div class="p-3">
                    <h3 class="font-semibold text-gray-800 text-sm truncate mb-1">
                        <a href="{{ route('member.ebooks.show', $ebook->id) }}" class="hover:text-blue-600 transition-colors">
                            {{ $ebook->title }}
                        </a>
                    </h3>
                    <p class="text-xs text-gray-500 truncate mb-2">
                        {{ $ebook->authors->pluck('full_name')->join(', ') ?: 'Unknown Author' }}
                    </p>

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
                        <a href="{{ route('member.ebooks.read', $ebook->id) }}"
                           class="block text-center bg-green-600 hover:bg-green-700 text-white text-xs font-medium py-1.5 rounded-lg transition-colors">
                            Continue Reading
                        </a>
                    @else
                        <form action="{{ route('member.ebooks.access', $ebook->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium py-1.5 rounded-lg transition-colors">
                                Read Now
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <div>{{ $ebooks->withQueryString()->links() }}</div>
    @endif

</div>
@endsection
