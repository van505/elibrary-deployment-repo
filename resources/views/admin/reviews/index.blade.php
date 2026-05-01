@extends('layouts.admin')
@section('title', 'Reviews')

@push('breadcrumbs')
<nav class="flex items-center text-sm" aria-label="Breadcrumb">
    <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-gray-600 transition-colors duration-150">Dashboard</a>
    <span class="text-gray-300 mx-1.5 select-none">›</span>
    <span class="text-gray-400 mx-1.5">Community</span>
    <span class="text-gray-300 mx-1.5 select-none">›</span>
    <span class="text-gray-700 font-medium">Reviews</span>
</nav>
@endpush

@section('content')
<div class="space-y-5">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Reviews</h1>
            <p class="text-gray-500 text-sm mt-1">Moderate member reviews and ratings</p>
        </div>
    </div>

    <x-admin.filter-bar :action="route('admin.reviews.index')" searchPlaceholder="Search by ebook title..." :sortable="['created_at' => 'Date Submitted', 'rating' => 'Rating']">
        <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            <option value="">All Statuses</option>
            <option value="pending"  @selected(request('status') === 'pending')>Pending</option>
            <option value="approved" @selected(request('status') === 'approved')>Approved</option>
            <option value="rejected" @selected(request('status') === 'rejected')>Rejected</option>
        </select>
        <select name="rating" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            <option value="">All Ratings</option>
            @for($i = 5; $i >= 1; $i--)
                <option value="{{ $i }}" @selected(request('rating') == $i)>{{ $i }} Stars</option>
            @endfor
        </select>
    </x-admin.filter-bar>

    <form id="bulkForm" action="{{ route('admin.reviews.bulk') }}" method="POST">
        @csrf

        <div id="bulkBar" class="hidden bg-blue-50 border border-blue-200 rounded-xl px-4 py-3 flex flex-wrap items-center gap-3 mb-3">
            <span id="selectedCount" class="text-sm font-medium text-blue-700">0 selected</span>
            <select name="action" required class="border border-blue-300 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                <option value="">— Choose action —</option>
                <option value="approved">✅ Approve Selected</option>
                <option value="rejected">❌ Reject Selected</option>
            </select>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1.5 rounded-lg text-sm font-medium transition-colors">Apply</button>
            <button type="button" onclick="clearSelection()" class="text-gray-500 hover:text-gray-700 text-sm">Clear</button>
        </div>

        <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 w-10">
                                <input type="checkbox" id="selectAll" onchange="toggleAll(this)" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </th>
                            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Ebook</th>
                            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Comment</th>
                            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($reviews as $r)
                        @php
                            $memberName = $r->member?->full_name ?: ($r->member?->user?->email ?? 'Unknown');
                            $initial = strtoupper(substr($memberName, 0, 1));
                            $sc = [
                                'pending'  => ['pill' => 'bg-yellow-50 text-yellow-700', 'dot' => 'bg-yellow-400'],
                                'approved' => ['pill' => 'bg-green-50 text-green-700',   'dot' => 'bg-green-500'],
                                'rejected' => ['pill' => 'bg-red-50 text-red-700',       'dot' => 'bg-red-400'],
                            ];
                        @endphp
                        <tr class="hover:bg-gray-50/60 transition-colors duration-200">
                            <td class="px-4 py-3">
                                <input type="checkbox" name="review_ids[]" value="{{ $r->id }}"
                                       class="review-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                       onchange="updateBulkBar()">
                            </td>
                            {{-- Member Avatar + Name --}}
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center text-xs font-bold flex-shrink-0">
                                        {{ $initial }}
                                    </div>
                                    <span class="text-sm font-medium text-gray-800 whitespace-nowrap">{{ $memberName }}</span>
                                </div>
                            </td>
                            {{-- Ebook --}}
                            <td class="px-4 py-3 max-w-[160px]">
                                <span class="text-sm text-gray-700 line-clamp-1 font-medium">{{ $r->ebook->title }}</span>
                            </td>
                            {{-- SVG Stars --}}
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-0.5">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= $r->rating ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                            </td>
                            {{-- Clamped Comment --}}
                            <td class="px-4 py-3 max-w-[220px]">
                                <p class="text-sm text-gray-600 line-clamp-2 leading-snug">{{ $r->comment ?? '—' }}</p>
                            </td>
                            {{-- Status Pill --}}
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-0.5 rounded-full {{ $sc[$r->status]['pill'] ?? 'bg-gray-50 text-gray-500' }}">
                                    <span class="w-1.5 h-1.5 rounded-full inline-block {{ $sc[$r->status]['dot'] ?? 'bg-gray-400' }}"></span>
                                    {{ ucfirst($r->status) }}
                                </span>
                            </td>
                            {{-- Date --}}
                            <td class="px-4 py-3 text-xs text-gray-400 whitespace-nowrap">{{ $r->created_at->format('M d, Y') }}</td>
                            {{-- Icon-only Actions --}}
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-1">
                                    @if($r->status === 'pending')
                                    {{-- Approve --}}
                                    <form action="{{ route('admin.reviews.approve', $r) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" title="Approve"
                                                class="p-1.5 text-emerald-300 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors duration-200">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </button>
                                    </form>
                                    {{-- Reject --}}
                                    <form action="{{ route('admin.reviews.update', $r) }}" method="POST">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" title="Reject"
                                                class="p-1.5 text-orange-300 hover:text-orange-600 hover:bg-orange-50 rounded-lg transition-colors duration-200">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </form>
                                    @endif
                                    {{-- Delete --}}
                                    <form action="{{ route('admin.reviews.destroy', $r) }}" method="POST" onsubmit="return confirm('Delete this review?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" title="Delete"
                                                class="p-1.5 text-rose-500 hover:text-rose-700 rounded-lg transition-colors duration-200">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="px-6 py-10 text-center text-gray-400">No reviews found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-100">{{ $reviews->withQueryString()->links() }}</div>
        </div>
    </form>
</div>

<script>
function toggleAll(master) {
    document.querySelectorAll('.review-checkbox').forEach(cb => cb.checked = master.checked);
    updateBulkBar();
}
function updateBulkBar() {
    const checked = document.querySelectorAll('.review-checkbox:checked').length;
    const bar = document.getElementById('bulkBar');
    document.getElementById('selectedCount').textContent = checked + ' selected';
    bar.classList.toggle('hidden', checked === 0);
    bar.classList.toggle('flex', checked > 0);
    const total = document.querySelectorAll('.review-checkbox').length;
    document.getElementById('selectAll').checked = checked === total && total > 0;
    document.getElementById('selectAll').indeterminate = checked > 0 && checked < total;
}
function clearSelection() {
    document.querySelectorAll('.review-checkbox').forEach(cb => cb.checked = false);
    document.getElementById('selectAll').checked = false;
    updateBulkBar();
}
</script>
@endsection
