@props([
    'action' => url()->current(),
    'searchPlaceholder' => 'Search records...',
    'sortable' => ['created_at' => 'Added Date'],
    'createRoute' => null,
    'createLabel' => 'Create New',
    'showSearch' => true,
    'showSort' => true,
    'enableEbookAutocomplete' => false
])

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
        <h2 class="text-lg font-bold text-gray-800">Filters & Sorting</h2>
        <div class="flex gap-2">
            @if($createRoute)
            <a href="{{ $createRoute }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                + {{ $createLabel }}
            </a>
            @endif
        </div>
    </div>

    <form method="GET" action="{{ $action }}" class="flex flex-wrap items-center gap-3">
        @if($showSearch)
        <!-- Search Input -->
        <div class="relative flex-1 min-w-[200px]">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ $searchPlaceholder }}"
                   id="{{ $enableEbookAutocomplete ? 'admin-filter-autocomplete' : '' }}" {{ $enableEbookAutocomplete ? 'autocomplete=off' : '' }}
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            @if($enableEbookAutocomplete)
            <x-search-autocomplete-js input-id="admin-filter-autocomplete" :is-admin="true" />
            @endif
        </div>
        @endif

        <!-- Custom Dropdowns Slot -->
        {{ $slot }}

        @if($showSort)
        <!-- Sort Column -->
        <select name="sort" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            @foreach($sortable as $col => $label)
                <option value="{{ $col }}" @selected(request('sort', 'created_at') === $col)>Sort: {{ $label }}</option>
            @endforeach
        </select>

        <!-- Sort Direction -->
        <select name="dir" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            <option value="asc" @selected(request('dir') === 'asc')>Ascending</option>
            <option value="desc" @selected(request('dir', 'desc') === 'desc')>Descending</option>
        </select>
        @endif

        <!-- Submit & Clear Buttons -->
        <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
            Apply
        </button>
        
        @php
            $hasFilters = !empty(request()->except(['page', 'clear']));
        @endphp
        @if($hasFilters)
        <a href="{{ $action }}?clear=1" class="text-red-500 hover:text-red-700 text-sm font-medium px-2 py-2 transition-colors">
            Clear Filters
        </a>
        @endif
    </form>
</div>
