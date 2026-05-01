@extends('layouts.admin')
@section('title', 'Collections')

@push('breadcrumbs')
<nav class="flex items-center text-sm" aria-label="Breadcrumb">
    <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-gray-600 transition-colors duration-150">Dashboard</a>
    <span class="text-gray-300 mx-1.5 select-none">›</span>
    <span class="text-gray-400 mx-1.5">Library</span>
    <span class="text-gray-300 mx-1.5 select-none">›</span>
    <span class="text-gray-700 font-medium">Collections</span>
</nav>
@endpush

@section('content')
<div class="h-full" x-data="Object.assign(collectionDrawer(), { viewMode: localStorage.getItem('collectionsView') || 'grid' })" @keydown.window.escape="open = false">
    <div class="space-y-5">

    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-2">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Collections</h1>
            <p class="text-sm text-gray-500 mt-1">Group related ebooks into sequential series or thematic collections</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="flex items-center bg-gray-100 rounded-lg p-1 gap-1">
                <button @click="viewMode = 'grid'; localStorage.setItem('collectionsView', 'grid')"
                        :class="viewMode === 'grid' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-500 hover:text-gray-700'"
                        class="p-1.5 rounded-md transition-all duration-150" title="Grid View">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                </button>
                <button @click="viewMode = 'table'; localStorage.setItem('collectionsView', 'table')"
                        :class="viewMode === 'table' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-500 hover:text-gray-700'"
                        class="p-1.5 rounded-md transition-all duration-150" title="Table View">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 6h18M3 14h18M3 18h18"/></svg>
                </button>
            </div>
            <button @click="openCreate()" class="inline-flex items-center gap-2 px-4 py-2 bg-teal-600 text-white text-sm font-medium rounded-lg hover:bg-teal-700 transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Create Collection
            </button>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-2">
        <div class="bg-white rounded-xl border border-gray-100 border-t-2 border-t-teal-500 shadow-[0_4px_6px_-1px_rgba(0,0,0,0.07),0_2px_4px_-2px_rgba(0,0,0,0.05)] p-5 flex items-center gap-4 transition-all duration-200 hover:shadow-[0_10px_15px_-3px_rgba(0,0,0,0.08),0_4px_6px_-4px_rgba(0,0,0,0.05)] hover:-translate-y-0.5">
            <div class="w-11 h-11 bg-teal-50 rounded-xl flex items-center justify-center flex-shrink-0 ring-1 ring-teal-100 shadow-sm">
                <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
            <div><div class="text-2xl font-bold text-gray-900">{{ $totalCollections }}</div><div class="text-sm text-gray-500">Total Collections</div></div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 border-t-2 border-t-teal-500 shadow-[0_4px_6px_-1px_rgba(0,0,0,0.07),0_2px_4px_-2px_rgba(0,0,0,0.05)] p-5 flex items-center gap-4 transition-all duration-200 hover:shadow-[0_10px_15px_-3px_rgba(0,0,0,0.08),0_4px_6px_-4px_rgba(0,0,0,0.05)] hover:-translate-y-0.5">
            <div class="w-11 h-11 bg-emerald-50 rounded-xl flex items-center justify-center flex-shrink-0 ring-1 ring-emerald-100 shadow-sm">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div><div class="text-2xl font-bold text-gray-900">{{ $activeCollections }}</div><div class="text-sm text-gray-500">Active</div></div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 border-t-2 border-t-teal-500 shadow-[0_4px_6px_-1px_rgba(0,0,0,0.07),0_2px_4px_-2px_rgba(0,0,0,0.05)] p-5 flex items-center gap-4 transition-all duration-200 hover:shadow-[0_10px_15px_-3px_rgba(0,0,0,0.08),0_4px_6px_-4px_rgba(0,0,0,0.05)] hover:-translate-y-0.5">
            <div class="w-11 h-11 bg-teal-50 rounded-xl flex items-center justify-center flex-shrink-0 ring-1 ring-teal-100 shadow-sm">
                <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13"/></svg>
            </div>
            <div><div class="text-2xl font-bold text-gray-900">{{ $totalEbooksInCollections }}</div><div class="text-sm text-gray-500">Total Ebooks</div></div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 border-t-2 border-t-teal-500 shadow-[0_4px_6px_-1px_rgba(0,0,0,0.07),0_2px_4px_-2px_rgba(0,0,0,0.05)] p-5 flex items-center gap-4 transition-all duration-200 hover:shadow-[0_10px_15px_-3px_rgba(0,0,0,0.08),0_4px_6px_-4px_rgba(0,0,0,0.05)] hover:-translate-y-0.5">
            <div class="w-11 h-11 bg-teal-50 rounded-xl flex items-center justify-center flex-shrink-0 ring-1 ring-teal-100 shadow-sm">
                <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
            <div><div class="text-2xl font-bold text-gray-900">{{ $avgEbooksPerCollection }}</div><div class="text-sm text-gray-500">Avg Ebooks</div></div>
        </div>
    </div>



    {{-- ── Filter Bar ──────────────────────────────────────────── --}}
    <form action="{{ route('admin.collections.index') }}" method="GET"
          class="flex flex-wrap items-center gap-3">
        <div class="flex-1 min-w-[200px] relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search collections..."
                   class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        <select name="status" onchange="this.form.submit()"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            <option value="">All Statuses</option>
            <option value="active" @selected(request('status') === 'active')>Active</option>
            <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
        </select>
        <select name="sort" onchange="this.form.submit()"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            <option value="latest" @selected(request('sort','latest') === 'latest')>Newest First</option>
            <option value="oldest" @selected(request('sort') === 'oldest')>Oldest First</option>
            <option value="name_asc" @selected(request('sort') === 'name_asc')>Name (A–Z)</option>
            <option value="name_desc" @selected(request('sort') === 'name_desc')>Name (Z–A)</option>
        </select>
        <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg text-sm font-medium hover:bg-gray-700 transition-colors">Filter</button>
        @if(request()->hasAny(['search','status','sort']))
            <a href="{{ route('admin.collections.index') }}" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors">Clear</a>
        @endif
    </form>

    {{-- ── Collections Grid/Table ─────────────────────────────── --}}
    {{-- Grid View --}}
    <div x-show="viewMode === 'grid'" style="display:none;" x-data="{ show: false }" x-init="setTimeout(() => show = true, 50)">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @forelse($collections as $index => $collection)
            @php
                $colInlineData = ['name' => $collection->name, 'description' => $collection->description ?? '', 'is_active' => (bool)$collection->is_active, 'currentCover' => $collection->cover_image ? Storage::url($collection->cover_image) : null];
            @endphp
            <div x-show="show"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 style="transition-delay: {{ ($index % 8) * 50 }}ms; display:none;"
                 class="bg-white rounded-xl border border-gray-100 shadow-[0_2px_4px_-1px_rgba(0,0,0,0.06),0_1px_2px_-1px_rgba(0,0,0,0.04)] overflow-hidden hover:-translate-y-1 hover:shadow-[0_12px_20px_-4px_rgba(0,0,0,0.1),0_4px_6px_-4px_rgba(0,0,0,0.06)] hover:border-teal-200 transition-all duration-200 ease-out">
                {{-- Cover image or fallback gradient --}}
                @if($collection->cover_image)
                    <img src="{{ asset('storage/' . $collection->cover_image) }}"
                         alt="{{ $collection->name }}"
                         class="w-full h-28 object-cover"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="h-28 w-full bg-gradient-to-br from-teal-400 to-teal-600 items-center justify-center hidden">
                        <h3 class="text-white font-bold text-center px-4 leading-snug drop-shadow">{{ Str::limit($collection->name, 20) }}</h3>
                    </div>
                @else
                    <div class="h-28 bg-gradient-to-br from-teal-400 to-teal-600 flex items-center justify-center relative">
                        <h3 class="text-white font-bold text-center px-4 leading-snug drop-shadow">{{ $collection->name }}</h3>
                    </div>
                @endif
                {{-- Body --}}
                <div class="p-4 relative">
                    <div x-data="{ open: false }" class="absolute top-3 right-3">
                        <button @click="open = !open" @click.outside="open = false"
                                class="w-7 h-7 flex items-center justify-center text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                        </button>
                        <div x-show="open" style="display:none;"
                             x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 top-8 w-44 bg-white rounded-xl shadow-lg border border-gray-100 z-50 py-1">
                            <button @click="openEdit({{ $collection->id }}, {{ Js::from($colInlineData) }}); open = false"
                                    class="w-full flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 transition-colors">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Edit
                            </button>
                            <a href="{{ route('admin.collections.show', $collection) }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Manage Books
                            </a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <form action="{{ route('admin.collections.destroy', $collection) }}" method="POST" onsubmit="return confirm('Delete this collection?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="pr-6">
                        <h3 class="font-semibold text-gray-900 text-sm truncate">{{ $collection->name }}</h3>
                        @if($collection->description)
                            <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $collection->description }}</p>
                        @endif
                        <div class="flex items-center gap-2 mt-2">
                            <span class="bg-teal-50 text-teal-700 text-xs font-medium px-2 py-0.5 rounded-full">{{ $collection->ebooks_count ?? 0 }} ebooks</span>
                            @if($collection->is_active)
                                <span class="bg-emerald-50 text-emerald-700 text-xs font-medium px-2 py-0.5 rounded-full">Active</span>
                            @else
                                <span class="bg-gray-50 text-gray-500 text-xs font-medium px-2 py-0.5 rounded-full">Inactive</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-16 text-gray-500">No collections found.</div>
            @endforelse
        </div>
        <div class="mt-4">{{ $collections->links() }}</div>
    </div>

    {{-- Table View --}}
    <div x-show="viewMode === 'table'" style="display:none;" class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Collection Info</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Books</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Created</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wide">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($collections as $collection)
                    @php
                        $colInlineData = [
                            'name'         => $collection->name,
                            'description'  => $collection->description ?? '',
                            'is_active'    => (bool) $collection->is_active,
                            'currentCover' => $collection->cover_image ? Storage::url($collection->cover_image) : null,
                        ];
                    @endphp
                    <tr class="hover:bg-gray-50/60 transition-colors">
                        {{-- Collection Info --}}
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                @if($collection->cover_image)
                                    <img src="{{ Storage::url($collection->cover_image) }}" class="w-9 h-12 object-cover rounded shadow-sm flex-shrink-0">
                                @else
                                    <div class="w-9 h-12 bg-indigo-50 rounded flex items-center justify-center flex-shrink-0 border border-indigo-100">
                                        <svg class="w-4 h-4 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <div class="font-semibold text-gray-900 text-sm truncate">{{ $collection->name }}</div>
                                    <div class="text-xs text-gray-400 truncate max-w-[180px]">{{ $collection->description ?: 'No description' }}</div>
                                </div>
                            </div>
                        </td>
                        {{-- Book Count --}}
                        <td class="px-4 py-3">
                            <span class="bg-blue-50 text-blue-700 text-xs font-semibold px-2.5 py-0.5 rounded-full">{{ $collection->ebooks_count }} items</span>
                        </td>
                        {{-- Status --}}
                        <td class="px-4 py-3">
                            @if($collection->is_active)
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-0.5 rounded-full bg-green-50 text-green-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span> Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-0.5 rounded-full bg-gray-50 text-gray-500 border border-gray-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400 inline-block"></span> Inactive
                                </span>
                            @endif
                        </td>
                        {{-- Created --}}
                        <td class="px-4 py-3 text-xs text-gray-400">{{ $collection->created_at->format('M d, Y') }}</td>
                        {{-- Actions --}}
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-1 justify-end">
                                {{-- Edit (pencil) --}}
                                <button type="button"
                                        @click="openEdit({{ $collection->id }}, {{ Js::from($colInlineData) }})"
                                        class="p-1.5 text-indigo-500 hover:text-indigo-700 rounded-lg transition-colors duration-200"
                                        title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>

                                {{-- Three-dots dropdown --}}
                                <div x-data="{ open: false }" class="relative">
                                    <button @click="open = !open" @click.outside="open = false"
                                            class="p-1.5 text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors"
                                            title="More actions">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                                    </button>
                                    <div x-show="open"
                                         x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="transform opacity-0 scale-95"
                                         x-transition:enter-end="transform opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="transform opacity-100 scale-100"
                                         x-transition:leave-end="transform opacity-0 scale-95"
                                         class="absolute right-8 top-0 w-44 bg-white rounded-xl shadow-lg border border-gray-100 z-50 py-1"
                                         style="display: none;">
                                        <a href="{{ route('admin.collections.show', $collection) }}"
                                           class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0m6 3a2 2 0 11-4 0 2 2 0 014 0M7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                            Manage Books
                                        </a>
                                        <div class="border-t border-gray-100 my-1"></div>
                                        <form action="{{ route('admin.collections.destroy', $collection) }}" method="POST"
                                              onsubmit="return confirm('Delete this collection?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                                <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">
                        <div class="text-center py-16">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            </div>
                            <h3 class="text-gray-900 font-semibold mb-1">No collections yet</h3>
                            <p class="text-gray-500 text-sm mb-4">Create your first collection to group related ebooks.</p>
                            <button @click="openCreate()" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Create Collection
                            </button>
                        </div>
                    </td></tr>
                @endforelse
            </tbody>
        </table>
        @if($collections->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">{{ $collections->links() }}</div>
        @endif
    </div>{{-- /space-y-5 --}}

    {{-- ── Reusable Drawer Component ──────────────────────────────── --}}
    <x-admin.drawer
        title="Create Collection"
        edit-title="Edit Collection"
        form-action="{{ route('admin.collections.store') }}">

        {{-- Collection Name --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Collection Name <span class="text-red-500">*</span></label>
            <input type="text" name="name" x-model="formData.name" required
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        {{-- Description --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="description" x-model="formData.description" rows="4"
                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                      placeholder="A short description of this collection..."></textarea>
        </div>

        {{-- Cover Image — Drag & Drop --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Cover Image</label>
            <div x-show="isEdit && formData.currentCover" class="mb-3">
                <img :src="formData.currentCover" class="w-20 h-28 object-cover rounded shadow-sm border border-gray-200">
                <p class="text-xs text-gray-500 mt-1">Upload a new image to replace</p>
            </div>
            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-indigo-400 transition-colors bg-gray-50"
                 x-data="{ dragging: false }"
                 @dragover.prevent="dragging = true"
                 @dragleave.prevent="dragging = false"
                 @drop.prevent="dragging = false; $refs.fileInput.files = $event.dataTransfer.files"
                 :class="{ 'border-indigo-500 bg-indigo-50': dragging }">
                <div class="space-y-1 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <div class="flex text-sm text-gray-600 justify-center">
                        <label class="relative cursor-pointer bg-transparent rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                            <span>Upload a file</span>
                            <input x-ref="fileInput" type="file" name="cover_image" class="sr-only" accept="image/*">
                        </label>
                        <p class="pl-1">or drag and drop</p>
                    </div>
                    <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                </div>
            </div>
        </div>

        {{-- Status Toggle --}}
        <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg border border-gray-100">
            <div>
                <label class="text-sm font-medium text-gray-900">Active Status</label>
                <p class="text-xs text-gray-500">Is this collection visible to users?</p>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" x-model="formData.is_active" class="sr-only peer">
                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
            </label>
        </div>

    </x-admin.drawer>

</div>{{-- /h-full x-data --}}

<script>
window.collectionDrawer = function() {
    return {
        open: false,
        isEdit: false,
        editId: null,
        formUrl: '{{ route('admin.collections.store') }}',

        formData: {
            name: '',
            description: '',
            is_active: true,
            currentCover: null,
        },

        openCreate() {
            this.isEdit = false;
            this.editId = null;
            this.formUrl = '{{ route('admin.collections.store') }}';
            this.formData = { name: '', description: '', is_active: true, currentCover: null };
            this.open = true;
        },

        openEdit(id, data) {
            this.isEdit = true;
            this.editId = id;
            this.formUrl = `/admin/collections/${id}`;
            this.formData.name         = data.name;
            this.formData.description  = data.description || '';
            this.formData.is_active    = data.is_active;
            this.formData.currentCover = data.currentCover || null;
            this.open = true;
        }
    };
};
</script>
@endsection
