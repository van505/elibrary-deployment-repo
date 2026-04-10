@extends('layouts.admin')
@section('title', 'Reviews')

@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between mb-2">
        <h1 class="text-xl font-bold text-gray-800">Reviews Management</h1>
    </div>

    <x-admin.filter-bar 
        :action="route('admin.reviews.index')" 
        searchPlaceholder="Search by ebook title..."
        :sortable="['created_at' => 'Date Submitted', 'rating' => 'Rating']">
        
        <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            <option value="">All Statuses</option>
            <option value="pending" @selected(request('status') === 'pending')>Pending</option>
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

    {{-- Bulk Action Form --}}
    <form id="bulkForm" action="{{ route('admin.reviews.bulk') }}" method="POST">
        @csrf

        {{-- Bulk Action Bar (hidden by default, shown via JS) --}}
        <div id="bulkBar" class="hidden bg-blue-50 border border-blue-200 rounded-xl px-4 py-3 flex flex-wrap items-center gap-3 mb-3">
            <span id="selectedCount" class="text-sm font-medium text-blue-700">0 selected</span>
            <select name="action" required class="border border-blue-300 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                <option value="">— Choose action —</option>
                <option value="approved">✅ Approve Selected</option>
                <option value="rejected">❌ Reject Selected</option>
            </select>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1.5 rounded-lg text-sm font-medium transition-colors">
                Apply
            </button>
            <button type="button" onclick="clearSelection()" class="text-gray-500 hover:text-gray-700 text-sm">Clear</button>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">
                                <input type="checkbox" id="selectAll" onchange="toggleAll(this)"
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </th>
                            <th class="px-6 py-3">Member</th>
                            <th class="px-6 py-3">Ebook</th>
                            <th class="px-6 py-3">Rating</th>
                            <th class="px-6 py-3">Comment</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Date</th>
                            <th class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $r)
                        <tr class="border-t border-gray-100 hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <input type="checkbox" name="review_ids[]" value="{{ $r->id }}"
                                       class="review-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                       onchange="updateBulkBar()">
                            </td>
                            <td class="px-6 py-3">{{ $r->member?->full_name ?: ($r->member?->user?->email ?? 'Unknown') }}</td>
                            <td class="px-6 py-3 max-w-xs truncate">{{ $r->ebook->title }}</td>
                            <td class="px-6 py-3">
                                <span class="text-yellow-500">{{ str_repeat('★', $r->rating) }}</span><span class="text-gray-300">{{ str_repeat('★', 5 - $r->rating) }}</span>
                            </td>
                            <td class="px-6 py-3 text-gray-500 max-w-xs truncate">{{ $r->comment ?? '—' }}</td>
                            <td class="px-6 py-3">
                                @php $sc = ['pending'=>'bg-yellow-100 text-yellow-700','approved'=>'bg-green-100 text-green-700','rejected'=>'bg-red-100 text-red-700']; @endphp
                                <span class="px-2 py-0.5 rounded text-xs font-medium {{ $sc[$r->status] ?? '' }}">{{ ucfirst($r->status) }}</span>
                            </td>
                            <td class="px-6 py-3 text-gray-400 whitespace-nowrap">{{ $r->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-3">
                                <div class="flex gap-2">
                                    @if($r->status === 'pending')
                                    <form action="{{ route('admin.reviews.approve', $r) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs">Approve</button>
                                    </form>
                                    <form action="{{ route('admin.reviews.update', $r) }}" method="POST">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">Reject</button>
                                    </form>
                                    @endif
                                    <form action="{{ route('admin.reviews.destroy', $r) }}" method="POST" onsubmit="return confirm('Delete review?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="bg-gray-400 hover:bg-gray-500 text-white px-3 py-1 rounded text-xs">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="px-6 py-8 text-center text-gray-400">No reviews found.</td></tr>
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

    // Sync select-all state
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
