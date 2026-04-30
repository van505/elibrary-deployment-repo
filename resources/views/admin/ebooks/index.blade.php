@extends('layouts.admin')
@section('title', 'Ebooks')

@section('content')
<div class="space-y-5" x-data="Object.assign(bulkActions({{ $ebooks->pluck('id')->toJson() }}), { viewMode: localStorage.getItem('ebooksView') || 'grid' })" @keydown.window.escape="open = false">

    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-2">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Ebooks</h1>
            <p class="text-sm text-gray-500 mt-1">Manage your digital library collection</p>
        </div>
        <div class="flex items-center gap-3">
            {{-- View Toggle --}}
            <div class="flex items-center bg-gray-100 rounded-lg p-1 gap-1">
                <button @click="viewMode = 'grid'; localStorage.setItem('ebooksView', 'grid')"
                        :class="viewMode === 'grid' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-500 hover:text-gray-700'"
                        class="p-1.5 rounded-md transition-all duration-150" title="Grid View">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                </button>
                <button @click="viewMode = 'table'; localStorage.setItem('ebooksView', 'table')"
                        :class="viewMode === 'table' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-500 hover:text-gray-700'"
                        class="p-1.5 rounded-md transition-all duration-150" title="Table View">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 6h18M3 14h18M3 18h18"/></svg>
                </button>
            </div>
            <button @click="$dispatch('open-ebook-drawer')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Ebook
            </button>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-2">
        <div class="bg-white rounded-xl border border-gray-100 border-t-2 border-t-indigo-500 shadow-[0_4px_6px_-1px_rgba(0,0,0,0.07),0_2px_4px_-2px_rgba(0,0,0,0.05)] p-5 flex items-center gap-4 transition-all duration-200 hover:shadow-[0_10px_15px_-3px_rgba(0,0,0,0.08),0_4px_6px_-4px_rgba(0,0,0,0.05)] hover:-translate-y-0.5">
            <div class="w-11 h-11 bg-indigo-50 rounded-xl flex items-center justify-center flex-shrink-0 ring-1 ring-indigo-100 shadow-sm">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            <div><div class="text-2xl font-bold text-gray-900">{{ $totalEbooks }}</div><div class="text-sm text-gray-500">Total Ebooks</div></div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 border-t-2 border-t-indigo-500 shadow-[0_4px_6px_-1px_rgba(0,0,0,0.07),0_2px_4px_-2px_rgba(0,0,0,0.05)] p-5 flex items-center gap-4 transition-all duration-200 hover:shadow-[0_10px_15px_-3px_rgba(0,0,0,0.08),0_4px_6px_-4px_rgba(0,0,0,0.05)] hover:-translate-y-0.5">
            <div class="w-11 h-11 bg-emerald-50 rounded-xl flex items-center justify-center flex-shrink-0 ring-1 ring-emerald-100 shadow-sm">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div><div class="text-2xl font-bold text-gray-900">{{ $activeEbooks }}</div><div class="text-sm text-gray-500">Active</div></div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 border-t-2 border-t-indigo-500 shadow-[0_4px_6px_-1px_rgba(0,0,0,0.07),0_2px_4px_-2px_rgba(0,0,0,0.05)] p-5 flex items-center gap-4 transition-all duration-200 hover:shadow-[0_10px_15px_-3px_rgba(0,0,0,0.08),0_4px_6px_-4px_rgba(0,0,0,0.05)] hover:-translate-y-0.5">
            <div class="w-11 h-11 bg-amber-50 rounded-xl flex items-center justify-center flex-shrink-0 ring-1 ring-amber-100 shadow-sm">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
            <div><div class="text-2xl font-bold text-gray-900">{{ $inactiveEbooks }}</div><div class="text-sm text-gray-500">Inactive</div></div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 border-t-2 border-t-indigo-500 shadow-[0_4px_6px_-1px_rgba(0,0,0,0.07),0_2px_4px_-2px_rgba(0,0,0,0.05)] p-5 flex items-center gap-4 transition-all duration-200 hover:shadow-[0_10px_15px_-3px_rgba(0,0,0,0.08),0_4px_6px_-4px_rgba(0,0,0,0.05)] hover:-translate-y-0.5">
            <div class="w-11 h-11 bg-indigo-50 rounded-xl flex items-center justify-center flex-shrink-0 ring-1 ring-indigo-100 shadow-sm">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0m6 3a2 2 0 11-4 0 2 2 0 014 0M7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <div><div class="text-2xl font-bold text-gray-900">{{ number_format($totalAccesses) }}</div><div class="text-sm text-gray-500">Total Reads</div></div>
        </div>
    </div>

    <x-admin.filter-bar 
        :action="route('admin.ebooks.index')" 
        searchPlaceholder="Search by title or author..."
        :sortable="['created_at' => 'Date Added', 'title' => 'Title', 'publish_year' => 'Publish Year']"
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

    {{-- ── Bulk Action Toolbar (Alpine.js controlled) ─────────────────────── --}}
    <div x-show="selectedIds.length > 0"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="flex items-center gap-3 bg-blue-50 border border-blue-200 rounded-xl px-5 py-3 flex-wrap shadow-sm"
         style="display: none;">
         
        <span class="text-sm font-semibold text-blue-800">
            <span x-text="selectedIds.length"></span> ebook(s) selected
        </span>
        <div class="flex items-center gap-2 ml-auto flex-wrap">
            <form x-ref="bulkForm" action="{{ route('admin.ebooks.bulk-action') }}" method="POST" class="flex items-center gap-2 flex-wrap">
                @csrf
                <input type="hidden" name="action" x-model="action">
                
                {{-- Dynamic array of selected IDs for the form --}}
                <template x-for="id in selectedIds" :key="id">
                    <input type="hidden" name="ebook_ids[]" :value="id">
                </template>

                <select x-model="action" class="border border-blue-300 bg-white rounded-lg px-3 py-2 text-sm text-gray-700 focus:ring-2 focus:ring-blue-500 outline-none shadow-sm transition-shadow">
                    <option value="" disabled>Choose action…</option>
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

                <button type="button" @click="applyBulkAction()"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors shadow-sm">
                    Apply
                </button>
            </form>

            <button type="button" @click="clearSelection()"
                class="text-sm text-gray-500 hover:text-gray-700 hover:bg-gray-100 border border-gray-300 px-3 py-2 rounded-lg transition-colors shadow-sm bg-white">
                Clear Selection
            </button>
        </div>
    </div>

    <div x-show="viewMode === 'table'" style="display:none;" class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 w-10">
                        <input type="checkbox"
                               class="w-4 h-4 rounded border-gray-300 text-blue-600 cursor-pointer focus:ring-blue-500"
                               title="Select all"
                               :checked="isAllSelected"
                               :indeterminate="isIndeterminate"
                               @change="toggleAll()">
                    </th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-4 py-3">Ebook Details</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-4 py-3">Category</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-4 py-3">Format</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-4 py-3">Access</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-4 py-3">Status</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($ebooks as $ebook)
                <tr class="hover:bg-gray-50 transition-colors row-item">
                    <td class="px-4 py-3">
                        <input type="checkbox" 
                               value="{{ $ebook->id }}"
                               x-model="selectedIds"
                               class="w-4 h-4 rounded border-gray-300 text-blue-600 cursor-pointer focus:ring-blue-500">
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-4">
                            @if($ebook->cover_image)
                                <img src="{{ asset('storage/' . $ebook->cover_image) }}" class="w-10 h-14 object-cover rounded shadow-sm flex-shrink-0">
                            @else
                                <div class="w-10 h-14 bg-blue-50 rounded flex items-center justify-center flex-shrink-0 border border-blue-100">
                                    <svg class="w-5 h-5 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13"/></svg>
                                </div>
                            @endif
                            <div class="min-w-0">
                                <div class="font-bold text-gray-900 text-sm truncate" title="{{ $ebook->title }}">{{ $ebook->title }}</div>
                                <div class="text-gray-500 text-xs mt-0.5 truncate" title="{{ $ebook->authors->pluck('full_name')->join(', ') }}">{{ $ebook->authors->pluck('full_name')->join(', ') ?: '—' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        @php
                            $colorHexMap = [
                                'red'    => '#f87171',
                                'orange' => '#fb923c',
                                'yellow' => '#facc15',
                                'green'  => '#34d399',
                                'blue'   => '#60a5fa',
                                'indigo' => '#818cf8',
                                'purple' => '#c084fc',
                                'pink'   => '#f472b6',
                                'slate'  => '#94a3b8',
                            ];
                            $catColorKey = $ebook->category->color ?? 'blue';
                            $catHex      = $colorHexMap[$catColorKey] ?? '#60a5fa';
                        @endphp
                        @if($ebook->category)
                            <span class="inline-flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full flex-shrink-0"
                                      style="background-color: {{ $catHex }}"></span>
                                <span class="text-xs font-medium" style="color: {{ $catHex }}">
                                    {{ $ebook->category->name }}
                                </span>
                            </span>
                        @else
                            <span class="text-xs text-gray-400">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <span class="bg-gray-50 text-gray-700 text-xs px-2.5 py-0.5 rounded-full font-medium uppercase border border-gray-100">{{ $ebook->file_type }}</span>
                    </td>
                    <td class="px-4 py-3">
                        @php $levelColors = ['free'=>'bg-green-50 text-green-700','basic'=>'bg-blue-50 text-blue-700','premium'=>'bg-purple-50 text-purple-700']; @endphp
                        <span class="text-xs font-medium px-2.5 py-0.5 rounded-full {{ $levelColors[$ebook->access_level] ?? 'bg-gray-50 text-gray-600' }}">
                            {{ ucfirst($ebook->access_level) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        @if(($ebook->status ?? 'active') === 'active')
                            <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-0.5 rounded-full bg-green-50 text-green-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span> Active
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-0.5 rounded-full bg-gray-50 text-gray-600 border border-gray-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400 inline-block"></span> Inactive
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-1">
                            @php
                                $ebookInlineData = [
                                    'title'        => $ebook->title,
                                    'category_id'  => (string) $ebook->category_id,
                                    'isbn'         => $ebook->isbn ?? '',
                                    'publisher'    => $ebook->publisher ?? '',
                                    'publish_year' => $ebook->publish_year ? (string)$ebook->publish_year : '',
                                    'file_type'    => $ebook->file_type,
                                    'access_level' => $ebook->access_level,
                                    'status'       => $ebook->status ?? 'active',
                                    'preview_pages'=> $ebook->preview_pages ?? 10,
                                    'tags'         => $ebook->tags->pluck('tag_name')->join(', '),
                                    'author_ids'   => $ebook->authors->pluck('id')->map(fn($id) => (int)$id)->values()->all(),
                                ];
                            @endphp
                            <button type="button"
                               @click="$dispatch('open-ebook-drawer-edit', { id: {{ $ebook->id }}, data: {{ Js::from($ebookInlineData) }} })"
                               class="p-1.5 text-indigo-500 hover:text-indigo-700 rounded-lg transition-colors duration-200"
                               title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            
                            {{-- Action Dropdown (Three Dots) --}}
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" @click.outside="open = false"
                                        class="p-1.5 text-slate-500 hover:text-slate-700 rounded-lg transition-colors duration-200 focus:outline-none"
                                        title="More Actions">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                                </button>
                                
                                <div x-show="open" 
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     class="absolute right-8 top-0 w-40 bg-white rounded-xl shadow-lg border border-gray-100 z-50 py-1"
                                     style="display: none;">
                                     
                                    <a href="{{ route('admin.ebooks.show', $ebook) }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0m6 3a2 2 0 11-4 0 2 2 0 014 0M7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        View Details
                                    </a>
                                    
                                    <form action="{{ route('admin.ebooks.spotlight', $ebook) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors {{ $ebook->is_spotlighted ? 'text-amber-600 bg-amber-50 hover:text-amber-700' : 'hover:text-amber-600' }}">
                                            @if($ebook->is_spotlighted)
                                                <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                Remove Spotlight
                                            @else
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                                Set Spotlight
                                            @endif
                                        </button>
                                    </form>
                                    
                                    <div class="border-t border-gray-100 my-1"></div>
                                    
                                    <form action="{{ route('admin.ebooks.destroy', $ebook) }}" method="POST" onsubmit="return confirm('Delete this ebook?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            Delete Ebook
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7">
                    <div class="text-center py-16">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <h3 class="text-gray-900 font-semibold mb-1">No ebooks yet</h3>
                        <p class="text-gray-500 text-sm mb-4">Get started by adding your first ebook.</p>
                        <a href="{{ route('admin.ebooks.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Add Ebook
                        </a>
                    </div>
                </td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100">{{ $ebooks->links() }}</div>
    </div>

<script>


document.addEventListener('alpine:init', () => {
    Alpine.data('bulkActions', (allIds) => ({
        selectedIds: [],
        allIds: allIds,
        action: '',
        get isAllSelected() {
            return this.selectedIds.length === this.allIds.length && this.allIds.length > 0;
        },
        get isIndeterminate() {
            return this.selectedIds.length > 0 && this.selectedIds.length < this.allIds.length;
        },
        toggleAll() {
            if (this.isAllSelected) {
                this.selectedIds = [];
            } else {
                this.selectedIds = [...this.allIds];
            }
        },
        clearSelection() {
            this.selectedIds = [];
            this.action = '';
        },
        applyBulkAction() {
            if (!this.action) {
                alert('Please select an action first.');
                return;
            }
            if (this.action === 'delete' && !confirm('Are you sure you want to delete these ebooks?')) {
                return;
            }
            this.$refs.bulkForm.submit();
        }
    }));
});
</script>

{{-- Grid View --}}
<div x-show="viewMode === 'grid'" style="display:none;">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @forelse($ebooks as $index => $ebook)
        <div x-show="viewMode === 'grid'"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             style="transition-delay: {{ ($index % 8) * 50 }}ms;"
             class="bg-white rounded-xl border border-gray-100 shadow-[0_2px_4px_-1px_rgba(0,0,0,0.06),0_1px_2px_-1px_rgba(0,0,0,0.04)] overflow-hidden hover:-translate-y-1 hover:shadow-[0_12px_20px_-4px_rgba(0,0,0,0.1),0_4px_6px_-4px_rgba(0,0,0,0.06)] hover:border-indigo-200 transition-all duration-200 ease-out group">
            {{-- Cover --}}
            @if($ebook->cover_image)
                <img src="{{ asset('storage/' . $ebook->cover_image) }}" class="h-40 w-full object-cover" alt="{{ $ebook->title }}">
            @else
                <div class="h-40 w-full bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center">
                    <svg class="w-12 h-12 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
            @endif
            {{-- Body --}}
            <div class="p-4 relative">
                {{-- Three-dot menu --}}
                <div x-data="{ open: false }" class="absolute top-3 right-3">
                    <button @click="open = !open" @click.outside="open = false"
                            class="w-7 h-7 flex items-center justify-center text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                    </button>
                    <div x-show="open" style="display:none;"
                         x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 top-8 w-40 bg-white rounded-xl shadow-lg border border-gray-100 z-50 py-1">
                        <button @click="$dispatch('open-ebook-drawer-edit', { id: {{ $ebook->id }}, data: {{ Js::from(['title' => $ebook->title, 'category_id' => (string) $ebook->category_id, 'isbn' => $ebook->isbn ?? '', 'publisher' => $ebook->publisher ?? '', 'publish_year' => $ebook->publish_year ? (string)$ebook->publish_year : '', 'file_type' => $ebook->file_type, 'access_level' => $ebook->access_level, 'status' => $ebook->status ?? 'active', 'preview_pages' => $ebook->preview_pages ?? 10, 'tags' => $ebook->tags->pluck('tag_name')->join(', '), 'author_ids' => $ebook->authors->pluck('id')->map(fn($id) => (int)$id)->values()->all()]) }} }); open = false"
                                class="w-full flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 transition-colors">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Edit
                        </button>
                        <a href="{{ route('admin.ebooks.show', $ebook) }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            View
                        </a>
                        <div class="border-t border-gray-100 my-1"></div>
                        <form action="{{ route('admin.ebooks.destroy', $ebook) }}" method="POST" onsubmit="return confirm('Delete this ebook?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
                <div class="pr-6">
                    <h3 class="font-semibold text-gray-900 truncate text-sm leading-snug">{{ $ebook->title }}</h3>
                    <p class="text-xs text-gray-500 mt-0.5 truncate">{{ $ebook->authors->pluck('full_name')->join(', ') ?: '—' }}</p>
                    <div class="flex items-center gap-2 mt-2 flex-wrap">
                        @if($ebook->category)
                            <span class="bg-indigo-50 text-indigo-700 text-xs rounded-full px-2 py-0.5 font-medium">{{ $ebook->category->name }}</span>
                        @endif
                        @if(($ebook->status ?? 'active') === 'active')
                            <span class="inline-flex items-center gap-1 text-xs font-medium px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span>Active</span>
                        @else
                            <span class="inline-flex items-center gap-1 text-xs font-medium px-2 py-0.5 rounded-full bg-gray-50 text-gray-500"><span class="w-1.5 h-1.5 rounded-full bg-gray-400 inline-block"></span>Inactive</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-16 text-gray-500">No ebooks found.</div>
        @endforelse
    </div>
    <div class="mt-4">{{ $ebooks->links() }}</div>
</div>

</div>

<x-admin.ebook-drawer :categories="$categories" :authors="$authors" />

@endsection
