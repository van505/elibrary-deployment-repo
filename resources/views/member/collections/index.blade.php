@extends('layouts.member')

@section('title', 'Collections')

@push('breadcrumbs')
<nav class="flex items-center text-sm">
    <a href="{{ route('member.dashboard') }}" class="text-gray-400 hover:text-gray-600 transition-colors duration-150">Home</a>
    <span class="text-gray-300 mx-1.5 select-none">›</span>
    <span class="text-gray-400 mx-1.5">Library</span>
    <span class="text-gray-300 mx-1.5 select-none">›</span>
    <span class="text-gray-700 font-medium">Collections</span>
</nav>
@endpush

@section('content')
<div class="space-y-6">
    
    {{-- Search & Filters --}}
    <form method="GET" class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 mb-6 flex flex-wrap gap-3 justify-between items-center">
        <div class="relative flex-1 min-w-[200px] max-w-md">
            <input type="text" name="search" value="{{ request('search') }}" autocomplete="off"
                   placeholder="Search Collections..."
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
        </div>
        <div class="flex items-center gap-3">
            <select name="sort" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                <option value="">Sort By: Newest</option>
                <option value="az" @selected(request('sort') === 'az')>Title (A-Z)</option>
            </select>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-medium transition-colors">Search</button>
            @if(request()->hasAny(['search', 'sort']))
                <a href="{{ route('member.collections.index') }}" class="text-gray-500 hover:text-gray-700 px-4 py-2 text-sm">Clear</a>
            @endif
        </div>
    </form>

    <div class="mb-6">
        <h3 class="text-xl font-bold text-gray-900 border-l-4 border-yellow-400 pl-3">Browse Series</h3>
        <p class="text-sm text-gray-500 mt-1 pl-4">Discover curated book collections and sequential series.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($collections as $collection)
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden flex flex-col group hover:shadow-md transition-shadow">
                {{-- Cover Image --}}
                <div class="aspect-video w-full relative bg-gray-100 overflow-hidden">
                    @if($collection->cover_image)
                        <img src="{{ Storage::url($collection->cover_image) }}" alt="{{ $collection->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-blue-500 to-indigo-600 flex flex-col items-center justify-center p-6 text-center text-white">
                            <svg class="w-10 h-10 opacity-50 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                    @endif
                    
                    {{-- Book Count Badge --}}
                    <div class="absolute top-3 right-3 bg-black/60 backdrop-blur-sm text-white text-xs font-medium px-2.5 py-1 rounded-full border border-white/10">
                        {{ $collection->ebooks_count }} {{ Str::plural('Book', $collection->ebooks_count) }}
                    </div>
                </div>
                
                {{-- Info --}}
                <div class="p-5 flex flex-col flex-grow">
                    <h3 class="text-lg font-bold text-gray-900 mb-1 leading-tight">{{ $collection->name }}</h3>
                    <p class="text-sm text-gray-500 line-clamp-2">{{ $collection->description ?: 'Explore this engaging book series.' }}</p>
                    
                    <div class="mt-auto pt-4">
                        <a href="{{ route('member.collections.show', $collection->slug) }}" class="block w-full bg-indigo-50 text-indigo-700 hover:bg-indigo-100 font-medium py-2 rounded-lg transition-colors text-center">
                            View Series
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center text-gray-500 bg-white rounded-xl shadow-sm border border-slate-200">
                <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                <p class="text-lg">No collections available yet.</p>
            </div>
        @endforelse
    </div>
    
    <div>
        {{ $collections->links() }}
    </div>
</div>
@endsection
