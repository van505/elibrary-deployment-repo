@extends('layouts.admin')
@section('title', $ebook->title)

@section('content')
<div class="mb-6 flex justify-between items-center">
    <a href="{{ route('admin.ebooks.index') }}" class="text-blue-600 hover:underline text-sm">← Back</a>
    <a href="{{ route('admin.ebooks.edit', $ebook) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm">Edit</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    <div class="bg-white rounded-xl shadow-sm p-6 flex flex-col items-center">
        @if($ebook->cover_image)
            <img src="{{ Storage::url($ebook->cover_image) }}" class="w-full max-w-xs rounded-lg shadow mb-4" onerror="this.src='/images/placeholder.png'">
        @else
            <img src="/images/placeholder.png" class="w-full max-w-xs rounded-lg shadow mb-4">
        @endif
    </div>

    <div class="lg:col-span-3 space-y-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">{{ $ebook->title }}</h2>
            <dl class="grid grid-cols-2 gap-4 text-sm">
                <div><dt class="text-gray-500">Author</dt><dd class="font-medium">{{ $ebook->author->name }}</dd></div>
                <div><dt class="text-gray-500">Category</dt><dd><span class="bg-blue-100 text-blue-700 text-xs px-2 py-0.5 rounded-full">{{ $ebook->category->name }}</span></dd></div>
                <div><dt class="text-gray-500">ISBN</dt><dd>{{ $ebook->isbn ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">Publisher</dt><dd>{{ $ebook->publisher ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">Publish Year</dt><dd>{{ $ebook->publish_year ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">Copies</dt><dd>{{ $ebook->available_copies }}/{{ $ebook->total_copies }} available</dd></div>
            </dl>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 font-semibold text-gray-800">Reviews</div>
            @forelse($ebook->reviews as $review)
            <div class="px-6 py-4 border-t border-gray-100">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-800">{{ $review->member->user->name ?? 'Unknown' }}</p>
                        <p class="text-xs text-gray-500">Rating: {{ $review->rating }}/5</p>
                    </div>
                    <span class="px-2 py-0.5 rounded text-xs {{ $review->status === 'approved' ? 'bg-green-100 text-green-700' : ($review->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">{{ $review->status }}</span>
                </div>
                @if($review->comment)<p class="text-sm text-gray-600 mt-1">{{ $review->comment }}</p>@endif
            </div>
            @empty
            <div class="px-6 py-6 text-center text-gray-400 text-sm">No reviews yet.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
