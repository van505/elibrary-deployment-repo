@extends('layouts.admin')
@section('title', 'Ebooks')

@section('content')
<div class="space-y-5">

    <x-admin.filter-bar 
        :action="route('admin.ebooks.index')" 
        searchPlaceholder="Search by title or author..."
        :sortable="['created_at' => 'Date Added', 'title' => 'Title', 'publish_year' => 'Publish Year']"
        :createRoute="route('admin.ebooks.create')"
        createLabel="Add Ebook"
        :enableEbookAutocomplete="true">
        
        <select name="category_id" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>{{ $cat->name }}</option>
            @endforeach
        </select>
        
        <select name="access_level" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            <option value="">All Access Levels</option>
            <option value="free" @selected(request('access_level') === 'free')>Free</option>
            <option value="basic" @selected(request('access_level') === 'basic')>Basic</option>
            <option value="premium" @selected(request('access_level') === 'premium')>Premium</option>
        </select>
        
    </x-admin.filter-bar>

    {{-- ── Bulk Action Toolbar (hidden until selection) ─────────────────────── --}}
    <div id="bulk-toolbar" class="hidden items-center gap-3 bg-blue-50 border border-blue-200 rounded-xl px-5 py-3 flex-wrap">
        <span class="text-sm font-semibold text-blue-800">
            <span id="bulk-count">0</span> ebook(s) selected
        </span>
        <div class="flex items-center gap-2 ml-auto flex-wrap">
            <form id="bulk-form" action="{{ route('admin.ebooks.bulk-action') }}" method="POST" class="flex items-center gap-2 flex-wrap">
                @csrf
                <input type="hidden" name="action" id="bulk-action-input" value="">
                {{-- Checkboxes appended by JS --}}
                <div id="bulk-ids-container"></div>

                <select id="bulk-action-select" class="border border-blue-300 bg-white rounded-lg px-3 py-2 text-sm text-gray-700 focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="" disabled selected>Choose action…</option>
                    <optgroup label="Status">
                        <option value="set_active">✅ Set Active</option>
                        <option value="set_inactive">🚫 Set Inactive</option>
                    </optgroup>
                    <optgroup label="Access Level">
                        <option value="set_free">🆓 Set Access: Free</option>
                        <option value="set_basic">🔵 Set Access: Basic</option>
                        <option value="set_premium">⭐ Set Access: Premium</option>
                    </optgroup>
                    <optgroup label="Danger Zone">
                        <option value="delete" class="text-red-600 font-semibold">🗑️ Delete Selected</option>
                    </optgroup>
                </select>

                <button type="button" onclick="applyBulkAction()"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
                    Apply
                </button>
            </form>

            <button type="button" onclick="clearSelection()"
                class="text-sm text-gray-500 hover:text-gray-700 border border-gray-300 px-3 py-2 rounded-lg transition-colors">
                Clear Selection
            </button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 w-10">
                        <input type="checkbox" id="select-all"
                               class="w-4 h-4 rounded border-gray-300 text-blue-600 cursor-pointer"
                               title="Select all">
                    </th>
                    <th class="text-left font-semibold text-gray-600 px-4 py-3">Cover</th>
                    <th class="text-left font-semibold text-gray-600 px-4 py-3">Title</th>
                    <th class="text-left font-semibold text-gray-600 px-4 py-3">Authors</th>
                    <th class="text-left font-semibold text-gray-600 px-4 py-3">Category</th>
                    <th class="text-left font-semibold text-gray-600 px-4 py-3">Format</th>
                    <th class="text-left font-semibold text-gray-600 px-4 py-3">Access</th>
                    <th class="text-left font-semibold text-gray-600 px-4 py-3">Status</th>
                    <th class="text-left font-semibold text-gray-600 px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($ebooks as $ebook)
                <tr class="hover:bg-gray-50 transition-colors row-item">
                    <td class="px-4 py-3">
                        <input type="checkbox" name="ebook_ids[]" value="{{ $ebook->id }}"
                               class="row-checkbox w-4 h-4 rounded border-gray-300 text-blue-600 cursor-pointer">
                    </td>
                    <td class="px-4 py-3">
                        @if($ebook->cover_image)
                            <img src="{{ asset('storage/' . $ebook->cover_image) }}" class="w-10 h-14 object-cover rounded shadow-sm">
                        @else
                            <div class="w-10 h-14 bg-blue-100 rounded flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13"/></svg>
                            </div>
                        @endif
                    </td>
                    <td class="px-4 py-3 font-medium text-gray-800 max-w-xs">{{ $ebook->title }}</td>
                    <td class="px-4 py-3 text-gray-500 text-xs">{{ $ebook->authors->pluck('full_name')->join(', ') ?: '—' }}</td>
                    <td class="px-4 py-3">
                        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-0.5 rounded-full">{{ $ebook->category->name ?? '—' }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded-full uppercase">{{ $ebook->file_type }}</span>
                    </td>
                    <td class="px-4 py-3">
                        @php $levelColors = ['free'=>'bg-green-100 text-green-700','basic'=>'bg-blue-100 text-blue-700','premium'=>'bg-purple-100 text-purple-700']; @endphp
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $levelColors[$ebook->access_level] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ ucfirst($ebook->access_level) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        @if(($ebook->status ?? 'active') === 'active')
                            <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5 rounded-full bg-green-100 text-green-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span> Active
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5 rounded-full bg-gray-100 text-gray-500">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400 inline-block"></span> Inactive
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex gap-2 flex-wrap">
                            <a href="{{ route('admin.ebooks.show', $ebook) }}" class="text-blue-600 hover:underline text-xs">View</a>
                            <a href="{{ route('admin.ebooks.edit', $ebook) }}" class="text-yellow-600 hover:underline text-xs">Edit</a>
                            <form action="{{ route('admin.ebooks.spotlight', $ebook) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-xs flex items-center gap-1 {{ $ebook->is_spotlighted ? 'text-amber-500 font-semibold' : 'text-gray-400 hover:text-amber-500' }}" title="{{ $ebook->is_spotlighted ? 'Spotlighted' : 'Set as Spotlight' }}">
                                    @if($ebook->is_spotlighted)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg> Spotlighted
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg> Set Spotlight
                                    @endif
                                </button>
                            </form>
                            <form action="{{ route('admin.ebooks.destroy', $ebook) }}" method="POST" onsubmit="return confirm('Delete this ebook?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline text-xs">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="px-6 py-10 text-center text-gray-400">No ebooks found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100">{{ $ebooks->links() }}</div>
    </div>
</div>

<script>
(function () {
    const selectAll    = document.getElementById('select-all');
    const bulkToolbar  = document.getElementById('bulk-toolbar');
    const bulkCount    = document.getElementById('bulk-count');
    const bulkIdsDiv   = document.getElementById('bulk-ids-container');
    const bulkSelect   = document.getElementById('bulk-action-select');
    const bulkActionIn = document.getElementById('bulk-action-input');
    const bulkForm     = document.getElementById('bulk-form');

    function getChecked() {
        return [...document.querySelectorAll('.row-checkbox:checked')];
    }

    function updateToolbar() {
        const checked = getChecked();
        const n = checked.length;
        bulkCount.textContent = n;

        if (n > 0) {
            bulkToolbar.classList.remove('hidden');
            bulkToolbar.classList.add('flex');
        } else {
            bulkToolbar.classList.add('hidden');
            bulkToolbar.classList.remove('flex');
        }

        // Header checkbox state
        const all = document.querySelectorAll('.row-checkbox');
        selectAll.indeterminate = n > 0 && n < all.length;
        selectAll.checked = all.length > 0 && n === all.length;
    }

    // Select All toggle
    selectAll.addEventListener('change', function () {
        document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = this.checked);
        updateToolbar();
    });

    // Individual row checkbox changes
    document.querySelectorAll('.row-checkbox').forEach(cb => {
        cb.addEventListener('change', updateToolbar);
    });

    // Apply bulk action
    window.applyBulkAction = function () {
        const action  = bulkSelect.value;
        const checked = getChecked();

        if (!action) {
            alert('Please choose an action from the dropdown.');
            return;
        }
        if (checked.length === 0) {
            alert('Please select at least one ebook.');
            return;
        }
        if (action === 'delete') {
            if (!confirm(`Are you sure you want to delete ${checked.length} ebook(s)? This cannot be undone.`)) return;
        }

        // Set hidden action input
        bulkActionIn.value = action;

        // Build hidden checkbox inputs dynamically inside the form
        bulkIdsDiv.innerHTML = '';
        checked.forEach(cb => {
            const input = document.createElement('input');
            input.type  = 'hidden';
            input.name  = 'ebook_ids[]';
            input.value = cb.value;
            bulkIdsDiv.appendChild(input);
        });

        bulkForm.submit();
    };

    // Clear selection
    window.clearSelection = function () {
        document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = false);
        selectAll.checked = false;
        selectAll.indeterminate = false;
        updateToolbar();
    };
})();
</script>
@endsection
