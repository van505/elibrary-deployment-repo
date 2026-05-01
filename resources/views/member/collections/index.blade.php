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
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="mb-8 pl-4 sm:pl-0">
            <h3 class="text-lg font-bold text-gray-900 border-l-4 border-yellow-400 pl-3">Browse Series</h3>
            <p class="text-sm text-gray-500 mt-1 pl-4">Discover curated book collections and sequential series.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 px-4 sm:px-0">
            @forelse($collections as $collection)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition group flex flex-col h-full">
                    {{-- Cover Image --}}
                    <div class="relative h-48 bg-gray-100 overflow-hidden">
                        @if($collection->cover_image)
                            <img src="{{ Storage::url($collection->cover_image) }}" alt="{{ $collection->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-blue-500 to-indigo-600 flex flex-col items-center justify-center p-6 text-center text-white">
                                <svg class="w-10 h-10 opacity-50 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                <span class="font-bold text-lg opacity-90 leading-tight shadow-sm p-1">{{ $collection->name }}</span>
                            </div>
                        @endif
                        
                        {{-- Book Count Badge --}}
                        <div class="absolute top-3 right-3 bg-black/70 backdrop-blur-sm text-white text-xs font-bold px-3 py-1 rounded-full flex items-center gap-1.5 shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            {{ $collection->ebooks_count }} {{ Str::plural('Book', $collection->ebooks_count) }}
                        </div>
                    </div>
                    
                    {{-- Info --}}
                    <div class="p-5 flex-1 flex flex-col">
                        <h3 class="font-bold text-gray-900 text-lg mb-2 leading-tight">{{ $collection->name }}</h3>
                        <p class="text-sm text-gray-500 line-clamp-2 mb-4 flex-1">{{ $collection->description ?: 'Explore this engaging book series.' }}</p>
                        
                        <a href="{{ route('member.collections.show', $collection->slug) }}" class="block w-full text-center bg-gray-50 hover:bg-yellow-400 border border-gray-200 hover:border-yellow-400 text-gray-900 font-bold py-2.5 rounded-xl transition-colors">
                            View Series
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center text-gray-500 bg-white rounded-2xl shadow-sm border border-gray-100">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    <p class="text-lg">No collections available yet.</p>
                </div>
            @endforelse
        </div>
        
        <div class="mt-8 px-4 sm:px-0">
            {{ $collections->links() }}
        </div>
        
    </div>
</div>
@endsection
