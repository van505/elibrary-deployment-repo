@extends('layouts.member')
@section('title', 'My Wishlist')

@push('breadcrumbs')
<nav class="flex items-center text-sm">
    <a href="{{ route('member.dashboard') }}" class="text-gray-400 hover:text-gray-600 transition-colors duration-150">Home</a>
    <span class="text-gray-300 mx-1.5 select-none">›</span>
    <span class="text-gray-400 mx-1.5">My Account</span>
    <span class="text-gray-300 mx-1.5 select-none">›</span>
    <span class="text-gray-700 font-medium">My Wishlist</span>
</nav>
@endpush

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">My Wishlist</h2>
    <p class="text-gray-500 text-sm mt-1">Ebooks you've saved for later</p>
</div>

@if($wishlistedEbooks->isEmpty())
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
        <div class="w-16 h-16 bg-purple-50 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
        </div>
        <h3 class="text-lg font-bold text-gray-900 mb-2">Your wishlist is empty.</h3>
        <p class="text-gray-500 max-w-sm mx-auto mb-6">Browse ebooks and add some titles you'd like to read in the future!</p>
        <a href="{{ route('member.dashboard') }}#books" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2.5 rounded-lg transition-colors inline-block text-sm shadow-sm">
            Browse Ebooks
        </a>
    </div>
@else
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5">
        @foreach($wishlistedEbooks as $ebook)
            @php
                $badgeColor = match ($ebook->access_level ?? 'free') {
                    'free'    => 'bg-green-100 text-green-700',
                    'basic'   => 'bg-blue-100 text-blue-700',
                    'premium' => 'bg-purple-100 text-purple-700',
                    default   => 'bg-gray-100 text-gray-600',
                };
                $badgeText = ucfirst($ebook->access_level ?? 'free');
                $authorsText = $ebook->authors->pluck('full_name')->join(', ') ?: 'Unknown Author';
            @endphp
            <div class="group bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm flex flex-col hover:shadow-md transition-shadow relative">
                
                {{-- Cover Image --}}
                <a href="{{ route('member.ebooks.show', $ebook->id) }}" class="relative w-full overflow-hidden bg-gray-100 aspect-[3/4] block">
                    @if($ebook->cover_image)
                        <img src="{{ Storage::url($ebook->cover_image) }}" alt="{{ $ebook->title }}" 
                             loading="lazy"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-50 to-blue-100">
                            <span class="text-xs text-blue-400 font-medium">No Cover</span>
                        </div>
                    @endif
                    
                    {{-- Badges --}}
                    <span class="absolute top-2.5 left-2.5 px-2 py-0.5 rounded-md text-[11px] font-bold {{ $badgeColor }}">
                        {{ $badgeText }}
                    </span>
                    
                    {{-- Hover overlay --}}
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                        <span class="bg-white text-gray-900 text-xs font-bold px-3 py-1.5 rounded-lg shadow-sm transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                            Read Now
                        </span>
                    </div>
                </a>

                {{-- Book Info --}}
                <div class="p-3 flex-1 flex flex-col">
                    <a href="{{ route('member.ebooks.show', $ebook->id) }}" class="font-semibold text-gray-900 text-sm line-clamp-1 hover:text-blue-600 transition-colors">
                        {{ $ebook->title }}
                    </a>
                    <p class="text-gray-500 text-xs mt-0.5 line-clamp-1 mb-3">{{ $authorsText }}</p>
                    
                    <div class="mt-auto flex items-center justify-between border-t border-gray-100 pt-3">
                        <span class="text-[10px] text-gray-400 font-medium">Added {{ $ebook->pivot->created_at->diffForHumans() }}</span>
                        
                        <form action="{{ route('member.wishlist.toggle', $ebook->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-red-500 hover:text-red-700 p-1 rounded hover:bg-red-50 transition-colors" title="Remove">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if($wishlistedEbooks->hasPages())
        <div class="mt-8 border-t border-gray-100 pt-6">
            {{ $wishlistedEbooks->links() }}
        </div>
    @endif
@endif
@endsection
