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

<div class="mb-6">
    <form method="GET" action="{{ $action }}" class="flex flex-col sm:flex-row items-center gap-3 w-full">
        @if($showSearch)
        <!-- Search Input -->
        <div class="relative flex-1 w-full">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ $searchPlaceholder }}"
                   id="{{ $enableEbookAutocomplete ? 'admin-filter-autocomplete' : '' }}" {{ $enableEbookAutocomplete ? 'autocomplete=off' : '' }}
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            @if($enableEbookAutocomplete)
            <x-search-autocomplete-js input-id="admin-filter-autocomplete" :is-admin="true" />
            @endif
        </div>
        @endif

        <div class="flex items-center gap-3 w-full sm:w-auto overflow-x-auto pb-1 sm:pb-0">
            <!-- Custom Dropdowns Slot -->
            {{ $slot }}

            @if($showSort)
            <!-- Sort Column -->
            <select name="sort" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none flex-shrink-0">
                @foreach($sortable as $col => $label)
                    <option value="{{ $col }}" @selected(request('sort', 'created_at') === $col)>Sort: {{ $label }}</option>
                @endforeach
            </select>

            <!-- Sort Direction -->
            <select name="dir" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none flex-shrink-0">
                <option value="asc" @selected(request('dir') === 'asc')>Ascending</option>
                <option value="desc" @selected(request('dir', 'desc') === 'desc')>Descending</option>
            </select>
            @endif

            <!-- Submit Button -->
            <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex-shrink-0">
                Apply
            </button>
            
            @php
                $hasFilters = !empty(request()->except(['page', 'clear']));
            @endphp
            @if($hasFilters)
            <a href="{{ $action }}?clear=1" class="text-red-500 hover:text-red-700 text-sm font-medium px-2 py-2 transition-colors flex-shrink-0">
                Clear
            </a>
            @endif
        </div>
    </form>
</div>
