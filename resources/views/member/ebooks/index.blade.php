@extends('layouts.member')
@section('title', 'Browse Ebooks')

@section('content')
<h2 class="text-2xl font-bold text-gray-800 mb-6">Browse Ebooks</h2>

{{-- Search & Filter --}}
<form method="GET" class="flex gap-3 mb-6">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title or author…" class="flex-1 border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
    <select name="category_id" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option value="">All Categories</option>
        @foreach($categories as $cat)
        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
        @endforeach
    </select>
    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">Search</button>
    @if(request('search') || request('category_id'))
    <a href="{{ route('member.ebooks.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm">Clear</a>
    @endif
</form>

{{-- Grid --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($ebooks as $ebook)
    <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow">
        <a href="{{ route('member.ebooks.show', $ebook) }}">
            <img src="{{ $ebook->cover_image ? Storage::url($ebook->cover_image) : '/images/placeholder.png' }}"
                 class="w-full h-48 object-cover"
                 onerror="this.src='/images/placeholder.png'">
        </a>
        <div class="p-4">
            <a href="{{ route('member.ebooks.show', $ebook) }}" class="font-bold text-gray-800 hover:text-blue-600 line-clamp-2 leading-snug">{{ $ebook->title }}</a>
            <p class="text-sm text-gray-500 mt-1">{{ $ebook->author->name }}</p>
            <div class="flex items-center justify-between mt-3">
                <span class="bg-blue-100 text-blue-700 text-xs px-2 py-0.5 rounded-full">{{ $ebook->category->name }}</span>
                <span class="text-xs font-medium {{ $ebook->available_copies > 0 ? 'text-green-600' : 'text-red-500' }}">
                    {{ $ebook->available_copies > 0 ? $ebook->available_copies.' available' : 'Unavailable' }}
                </span>
            </div>
            <div class="flex gap-2 mt-4">
                <form action="{{ route('member.borrowings.store') }}" method="POST" class="flex-1">
                    @csrf
                    <input type="hidden" name="ebook_id" value="{{ $ebook->id }}">
                    <button type="submit" {{ $ebook->available_copies < 1 ? 'disabled' : '' }}
                            class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white px-3 py-1.5 rounded text-sm font-medium">
                        Borrow
                    </button>
                </form>
                <form action="{{ route('member.reservations.store') }}" method="POST" class="flex-1">
                    @csrf
                    <input type="hidden" name="ebook_id" value="{{ $ebook->id }}">
                    <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1.5 rounded text-sm font-medium">Reserve</button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-3 py-16 text-center text-gray-400">
        <p class="text-lg">No ebooks found.</p>
        <a href="{{ route('member.ebooks.index') }}" class="text-blue-600 text-sm mt-2 inline-block">Clear search</a>
    </div>
    @endforelse
</div>

<div class="mt-6">{{ $ebooks->links() }}</div>
@endsection
