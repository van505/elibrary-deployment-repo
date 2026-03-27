@extends('layouts.member')
@section('title', 'My Reading History')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">My Reading History</h2>
        <p class="text-sm text-gray-500 mt-1">All ebooks you have accessed</p>
    </div>

    @if($accesses->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-16 text-center text-gray-400">
            <svg class="w-16 h-16 mx-auto mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            <p class="font-medium">You haven't read any ebooks yet.</p>
            <a href="{{ route('member.ebooks.index') }}" class="mt-3 inline-block text-blue-600 hover:underline text-sm">Browse Ebooks →</a>
        </div>
    @else
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
            @foreach($accesses as $access)
            @php $ebook = $access->ebook; @endphp
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow group">
                {{-- Cover --}}
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
                    <p class="text-xs text-gray-400 truncate mb-1">
                        {{ $ebook->authors->pluck('full_name')->join(', ') ?: 'Unknown Author' }}
                    </p>
                    @if($access->accessed_at)
                        <p class="text-xs text-gray-300 mb-3">Last accessed {{ $access->accessed_at->diffForHumans() }}</p>
                    @else
                        <p class="text-xs text-gray-300 mb-3">Added {{ $access->created_at->diffForHumans() }}</p>
                    @endif
                    <a href="{{ route('member.ebooks.read', $ebook->id) }}"
                       class="block text-center bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium py-1.5 rounded-lg transition-colors">
                        Read Again
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <div>{{ $accesses->links() }}</div>
    @endif
</div>
@endsection
