@extends('layouts.member')
@section('title', 'My Reading History')

@push('breadcrumbs')
<nav class="flex items-center text-sm">
    <a href="{{ route('member.dashboard') }}" class="text-gray-400 hover:text-gray-600 transition-colors duration-150">Home</a>
    <span class="text-gray-300 mx-1.5 select-none">›</span>
    <span class="text-gray-400 mx-1.5">My Account</span>
    <span class="text-gray-300 mx-1.5 select-none">›</span>
    <span class="text-gray-700 font-medium">Reading History</span>
</nav>
@endpush

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">My Reading History</h2>
            <p class="text-sm text-gray-500 mt-1">All ebooks you have accessed</p>
        </div>
        
        <div class="flex items-center gap-3">
            {{-- Grid/List Toggle --}}
            <div class="flex bg-white rounded-lg border border-slate-200 p-1 shadow-sm">
                <a href="{{ request()->fullUrlWithQuery(['view' => 'grid']) }}" class="p-1.5 rounded-md {{ request('view', 'grid') === 'grid' ? 'bg-indigo-50 text-indigo-600' : 'text-gray-400 hover:text-gray-600 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                </a>
                <a href="{{ request()->fullUrlWithQuery(['view' => 'list']) }}" class="p-1.5 rounded-md {{ request('view') === 'list' ? 'bg-indigo-50 text-indigo-600' : 'text-gray-400 hover:text-gray-600 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </a>
            </div>
            
            {{-- Clear History --}}
            @if($accesses->isNotEmpty())
            <form action="{{ route('member.my-ebooks.clear') }}" method="POST" onsubmit="return confirm('Are you sure you want to clear your reading history? This cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 bg-red-50 hover:bg-red-100 font-medium px-4 py-2 rounded-lg transition-colors text-sm border border-red-100 shadow-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Clear History
                </button>
            </form>
            @endif
        </div>
    </div>

    @if($accesses->isEmpty())
        <div class="bg-slate-50 border-2 border-dashed border-slate-200 rounded-xl p-16 text-center text-gray-400 flex flex-col items-center justify-center">
            <svg class="w-16 h-16 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            <p class="font-medium text-lg text-gray-700">Empty History</p>
            <p class="text-sm mt-1 mb-4">You haven't read any ebooks yet.</p>
            <a href="{{ route('member.ebooks.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-6 rounded-lg transition-colors">
                Browse Ebooks
            </a>
        </div>
    @else
        @php $isListView = request('view') === 'list'; @endphp
        
        <div class="{{ $isListView ? 'flex flex-col gap-4' : 'grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6 mt-6' }}">
            @foreach($accesses as $access)
                @php $ebook = $access->ebook; @endphp
                
                @if($isListView)
                    {{-- List View Item --}}
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 flex gap-6 relative group hover:shadow-md transition-shadow">
                        <div class="w-24 h-36 flex-shrink-0 bg-gray-100 rounded-lg overflow-hidden relative border border-gray-100">
                            <a href="{{ route('member.ebooks.show', $ebook->id) }}" class="block w-full h-full">
                                @if($ebook->cover_image)
                                    <img src="{{ Storage::url($ebook->cover_image) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-indigo-500 to-purple-600 text-white p-2 text-center text-xs font-bold">
                                        {{ $ebook->title }}
                                    </div>
                                @endif
                            </a>
                        </div>
                        
                        <div class="flex-1 flex flex-col justify-center py-2">
                            <a href="{{ route('member.ebooks.show', $ebook->id) }}" class="font-bold text-gray-900 text-lg hover:text-indigo-600 transition-colors line-clamp-1 mb-1">
                                {{ $ebook->title }}
                            </a>
                            <p class="text-sm text-gray-500 mb-2">{{ $ebook->authors->pluck('name')->join(', ') ?: 'Unknown Author' }}</p>
                            
                            @if($access->accessed_at)
                                <p class="text-xs text-indigo-600 font-medium mb-4">Last accessed {{ $access->accessed_at->diffForHumans() }}</p>
                            @else
                                <p class="text-xs text-indigo-600 font-medium mb-4">Added {{ $access->created_at->diffForHumans() }}</p>
                            @endif
                            
                            <div class="mt-auto">
                                <a href="{{ route('member.ebooks.read', $ebook->id) }}" class="inline-block px-5 py-2 border border-indigo-200 text-indigo-700 hover:bg-indigo-50 font-medium rounded-lg text-sm transition-colors shadow-sm">
                                    Read Again
                                </a>
                            </div>
                        </div>

                        {{-- Remove Button (List View) --}}
                        <form action="{{ route('member.my-ebooks.destroy', $access->id) }}" method="POST" class="absolute top-4 right-4" onsubmit="return confirm('Remove this from your history?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" title="Remove from history" class="text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg p-2 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                @else
                    {{-- Grid View Card --}}
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden flex flex-col relative group hover:shadow-md transition-shadow">
                        {{-- Cover Container --}}
                        <div class="relative w-full h-56 bg-gray-100 overflow-hidden border-b border-gray-100">
                            <a href="{{ route('member.ebooks.show', $ebook->id) }}" class="block w-full h-full">
                                @if($ebook->cover_image)
                                    <img src="{{ Storage::url($ebook->cover_image) }}" class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-indigo-500 to-purple-600 text-white p-4 text-center font-bold">
                                        {{ $ebook->title }}
                                    </div>
                                @endif
                            </a>
                            
                            {{-- Remove Action (Absolute Top Right) --}}
                            <form action="{{ route('member.my-ebooks.destroy', $access->id) }}" method="POST" class="absolute top-2 right-2 z-10" onsubmit="return confirm('Remove this from your history?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Remove from history" class="bg-white/90 hover:bg-red-50 text-gray-500 hover:text-red-600 rounded-full p-1.5 shadow-sm opacity-0 group-hover:opacity-100 transition-all focus:opacity-100 outline-none">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </form>
                        </div>
        
                        {{-- Card Content --}}
                        <div class="p-4 flex flex-col flex-grow">
                            <h3 class="font-semibold text-gray-900 line-clamp-1 mb-0.5" title="{{ $ebook->title }}">
                                <a href="{{ route('member.ebooks.show', $ebook->id) }}" class="hover:text-indigo-600 transition-colors">
                                    {{ $ebook->title }}
                                </a>
                            </h3>
                            <p class="text-xs text-gray-500 line-clamp-1">
                                {{ $ebook->authors->pluck('name')->join(', ') ?: 'Unknown Author' }}
                            </p>
                            
                            @if($access->accessed_at)
                                <p class="text-xs text-indigo-600 mt-1 font-medium">Last accessed {{ $access->accessed_at->diffForHumans() }}</p>
                            @else
                                <p class="text-xs text-indigo-600 mt-1 font-medium">Added {{ $access->created_at->diffForHumans() }}</p>
                            @endif
                            
                            <div class="mt-auto pt-4 w-full">
                                <a href="{{ route('member.ebooks.read', $ebook->id) }}"
                                   class="block w-full text-center border border-indigo-200 text-indigo-700 hover:bg-indigo-50 font-medium py-1.5 rounded text-sm transition-colors shadow-sm">
                                    Read Again
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <div class="mt-6">
            {{ $accesses->links() }}
        </div>
    @endif
</div>
@endsection
