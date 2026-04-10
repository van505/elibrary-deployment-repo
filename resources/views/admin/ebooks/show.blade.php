@extends('layouts.admin')
@section('title', $ebook->title)

@section('content')
<div class="mb-6 flex flex-wrap justify-between items-center gap-3">
    <a href="{{ route('admin.ebooks.index') }}" class="text-blue-600 hover:underline text-sm">← Back to Ebooks</a>
    <a href="{{ route('admin.ebooks.edit', $ebook) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm">Edit</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-6">
    {{-- Cover --}}
    <div class="bg-white rounded-xl shadow-sm p-6 flex flex-col items-center gap-4">
        @if($ebook->cover_image)
            <img src="{{ Storage::url($ebook->cover_image) }}" class="w-full max-w-xs rounded-lg shadow" onerror="this.src='/images/placeholder.png'">
        @else
            <div class="w-full aspect-[3/4] bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-16 h-16 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13"/></svg>
            </div>
        @endif

        {{-- Access Stats --}}
        <div class="w-full bg-blue-50 rounded-xl p-4 text-center">
            <p class="text-3xl font-bold text-blue-600">{{ $totalAccesses }}</p>
            <p class="text-xs text-gray-500 mt-1">Total Accesses</p>
        </div>

        @if($avgRating)
        <div class="w-full bg-yellow-50 rounded-xl p-4 text-center">
            <p class="text-2xl font-bold text-yellow-500">
                @for($i = 1; $i <= 5; $i++)
                    <span class="{{ $i <= round($avgRating) ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                @endfor
            </p>
            <p class="text-xs text-gray-500 mt-1">{{ number_format($avgRating, 1) }} / 5 avg rating</p>
        </div>
        @endif
    </div>

    {{-- Info + Recent Readers --}}
    <div class="lg:col-span-3 space-y-6">
        {{-- Metadata --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">{{ $ebook->title }}</h2>
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div><dt class="text-gray-500">Authors</dt><dd class="font-medium">{{ $ebook->authors->pluck('full_name')->join(', ') ?: '—' }}</dd></div>
                <div><dt class="text-gray-500">Category</dt><dd><span class="bg-blue-100 text-blue-700 text-xs px-2 py-0.5 rounded-full">{{ $ebook->category->name }}</span></dd></div>
                <div><dt class="text-gray-500">ISBN</dt><dd>{{ $ebook->isbn ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">Publisher</dt><dd>{{ $ebook->publisher ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">Publish Year</dt><dd>{{ $ebook->publish_year ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">Format</dt><dd><span class="bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded-full uppercase">{{ $ebook->file_type }}</span></dd></div>
                <div><dt class="text-gray-500">Access Level</dt>
                    @php $lc=['free'=>'bg-green-100 text-green-700','basic'=>'bg-blue-100 text-blue-700','premium'=>'bg-purple-100 text-purple-700']; @endphp
                    <dd><span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $lc[$ebook->access_level] ?? '' }}">{{ ucfirst($ebook->access_level) }}</span></dd>
                </div>
                <div class="sm:col-span-2 border-t border-gray-100 pt-3 mt-1 cursor-default">
                    <dt class="text-gray-500 mb-2 font-medium">Tags / Keywords</dt>
                    <dd class="flex flex-wrap gap-2">
                        @forelse($ebook->tags as $tag)
                            <span class="bg-gray-100 hover:bg-gray-200 transition-colors text-gray-700 text-xs px-3 py-1 rounded-full border border-gray-200">{{ $tag->tag_name }}</span>
                        @empty
                            <span class="text-gray-400 italic text-xs">No tags assigned.</span>
                        @endforelse
                    </dd>
                </div>
            </dl>
        </div>

        {{-- Recent Readers --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 font-semibold text-gray-800">Recent Readers (Last 10)</div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-3 text-left">Member</th>
                            <th class="px-6 py-3 text-left">Code</th>
                            <th class="px-6 py-3 text-left">Accessed</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentAccessors as $access)
                        <tr class="border-t border-gray-100 hover:bg-gray-50">
                            <td class="px-6 py-3 font-medium">
                                <a href="{{ route('admin.members.show', $access->member->id) }}" class="hover:text-blue-600">
                                    {{ $access->member?->full_name ?: '—' }}
                                </a>
                            </td>
                            <td class="px-6 py-3 font-mono text-xs text-gray-400">{{ $access->member?->member_code }}</td>
                            <td class="px-6 py-3 text-gray-400">{{ $access->accessed_at?->format('M d, Y') ?? $access->created_at->format('M d, Y') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="px-6 py-6 text-center text-gray-400">Not read yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Reviews --}}
<div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-gray-100 font-semibold text-gray-800 flex items-center justify-between">
        <span>Reviews ({{ $ebook->reviews->count() }})</span>
        @if($avgRating) <span class="text-yellow-500 text-sm">{{ number_format($avgRating, 1) }} ★ avg</span> @endif
    </div>
    @forelse($ebook->reviews as $review)
    <div class="px-6 py-4 border-t border-gray-100">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <p class="text-sm font-medium text-gray-800">{{ $review->member?->full_name ?: 'Unknown' }}</p>
                <div class="flex gap-0.5 mt-0.5">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="{{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-200' }}">★</span>
                    @endfor
                </div>
            </div>
            <div class="flex items-center gap-2">
                @php $sc=['pending'=>'bg-yellow-100 text-yellow-700','approved'=>'bg-green-100 text-green-700','rejected'=>'bg-red-100 text-red-700']; @endphp
                <span class="px-2 py-0.5 rounded text-xs font-medium {{ $sc[$review->status] ?? '' }}">{{ ucfirst($review->status) }}</span>
                @if($review->status === 'pending')
                <form action="{{ route('admin.reviews.update', $review) }}" method="POST" class="inline">
                    @csrf @method('PUT')
                    <input type="hidden" name="status" value="approved">
                    <button class="bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded text-xs">Approve</button>
                </form>
                <form action="{{ route('admin.reviews.update', $review) }}" method="POST" class="inline">
                    @csrf @method('PUT')
                    <input type="hidden" name="status" value="rejected">
                    <button class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs">Reject</button>
                </form>
                @endif
            </div>
        </div>
        @if($review->comment) <p class="text-sm text-gray-600 mt-2">{{ $review->comment }}</p> @endif
        <p class="text-xs text-gray-400 mt-1">{{ $review->created_at->format('M d, Y') }}</p>
    </div>
    @empty
    <div class="px-6 py-6 text-center text-gray-400 text-sm">No reviews yet.</div>
    @endforelse
</div>

{{-- File Preview --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">File Preview</h3>
    @if($ebook->file_path)
        <div class="flex gap-3 mb-4">
            <a href="{{ Storage::url($ebook->file_path) }}" target="_blank"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">Open in New Tab</a>
            <a href="{{ Storage::url($ebook->file_path) }}" download
               class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors">Download</a>
        </div>
        @if($ebook->file_type === 'pdf')
            <iframe src="{{ Storage::url($ebook->file_path) }}" class="w-full rounded-lg border border-gray-200" style="height:600px" title="{{ $ebook->title }}"></iframe>
        @else
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-6 text-center text-gray-400 text-sm">
                Preview not available for {{ strtoupper($ebook->file_type) }} files. Use the download button.
            </div>
        @endif
    @else
        <p class="text-gray-400 text-sm">No file uploaded.</p>
    @endif
</div>
@endsection
