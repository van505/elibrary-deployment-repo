@extends('layouts.admin')
@section('title', 'Ebooks')

@section('content')
<div class="space-y-5" x-data="{ ...bulkActions({{ $ebooks->pluck('id')->toJson() }}), ...ebookDrawer() }" @keydown.window.escape="open = false">

    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Ebooks</h1>
            <p class="text-gray-500 text-sm mt-1">Manage your digital library collection</p>
        </div>
        {{-- Slide-over Component --}}
        <div>
            <button @click="openCreate()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Ebook
            </button>

            <!-- Backdrop -->
            <div x-show="open" 
                 x-transition:enter="ease-in-out duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in-out duration-300" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0" 
                 class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity z-40" 
                 @click="open = false" style="display: none;"></div>

            <!-- Panel -->
            <div class="fixed inset-y-0 right-0 z-50 flex max-w-full pl-10" style="display: none;" x-show="open">
                <div x-show="open" 
                     x-transition:enter="transform transition ease-in-out duration-300 sm:duration-500" 
                     x-transition:enter-start="translate-x-full" 
                     x-transition:enter-end="translate-x-0" 
                     x-transition:leave="transform transition ease-in-out duration-300 sm:duration-500" 
                     x-transition:leave-start="translate-x-0" 
                     x-transition:leave-end="translate-x-full" 
                     class="w-screen max-w-lg pointer-events-auto">
                    
                    <form :action="formUrl" method="POST" enctype="multipart/form-data" class="flex flex-col h-full bg-white shadow-2xl">
                        @csrf
                        <input type="hidden" :name="isEdit ? '_method' : '_noop'" value="PUT">

                        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900" x-text="isEdit ? 'Edit Ebook' : 'Add New Ebook'"></h2>
                            <button type="button" @click="open = false" class="text-gray-400 hover:text-gray-500 transition-colors">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div class="flex-1 px-6 py-6 overflow-y-auto">
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                                    <input type="text" name="title" x-model="formData.title" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                </div>

                                {{-- Alpine.js Authors Multi-Select (state lives in root ebookDrawer scope) --}}
                                <div class="relative">
                                    
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Authors <span class="text-red-500">*</span></label>
                                    
                                    <!-- Hidden select to submit data -->
                                    <select name="author_ids[]" multiple class="hidden">
                                        <template x-for="id in selected" :key="id">
                                            <option :value="id" selected></option>
                                        </template>
                                    </select>
                                    
                                    <!-- Custom input trigger -->
                                    <div @click="authorDropdownOpen = !authorDropdownOpen" @click.outside="authorDropdownOpen = false"
                                         class="w-full border border-gray-300 rounded-lg shadow-sm focus-within:border-blue-500 focus-within:ring-1 focus-within:ring-blue-500 bg-white min-h-[38px] p-1.5 flex flex-wrap gap-1 cursor-text relative">
                                        
                                        <!-- Selected tags -->
                                        <template x-for="id in selected" :key="id">
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                                <span x-text="allAuthors.find(a => a.id === id)?.name"></span>
                                                <button type="button" @click.stop="toggleAuthor(id)" class="text-blue-400 hover:text-blue-600">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                </button>
                                            </span>
                                        </template>
                                        
                                        <input type="text" x-model="authorSearch" placeholder="Select authors..."
                                               class="flex-1 min-w-[100px] border-none shadow-none focus:ring-0 p-0 text-sm bg-transparent"
                                               @keydown.enter.prevent="if(filteredAuthors.length > 0) toggleAuthor(filteredAuthors[0].id)">
                                               
                                        <div class="absolute right-2 top-2.5 text-gray-400 pointer-events-none">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        </div>
                                    </div>
                                    
                                    <!-- Dropdown list -->
                                    <div x-show="authorDropdownOpen" style="display: none;"
                                         class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm">
                                        <template x-for="author in filteredAuthors" :key="author.id">
                                            <div @click="toggleAuthor(author.id)"
                                                 class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-gray-50 flex items-center justify-between"
                                                 :class="selected.includes(author.id) ? 'bg-blue-50 text-blue-900' : 'text-gray-900'">
                                                <span class="block truncate" :class="selected.includes(author.id) ? 'font-semibold' : 'font-normal'" x-text="author.name"></span>
                                                <span x-show="selected.includes(author.id)" class="text-blue-600 flex items-center pr-4">
                                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                                </span>
                                            </div>
                                        </template>
                                        <div x-show="filteredAuthors.length === 0" class="py-2 px-3 text-sm text-gray-500">No authors found</div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Category <span class="text-red-500">*</span></label>
                                        <select name="category_id" x-model="formData.category_id" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                            <option value="">Select Category</option>
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Format <span class="text-red-500">*</span></label>
                                        <select name="file_type" x-model="formData.file_type" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                            <option value="pdf">PDF</option>
                                            <option value="epub">EPUB</option>
                                            <option value="mp3">MP3 (Audiobook)</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Access Level <span class="text-red-500">*</span></label>
                                        <select name="access_level" x-model="formData.access_level" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                            <option value="free">Free</option>
                                            <option value="basic">Basic</option>
                                            <option value="premium">Premium</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                        <select name="status" x-model="formData.status" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Ebook File <span class="text-red-500">*</span></label>
                                        <input type="file" name="file_path" accept=".pdf,.epub,.mp3" :required="!isEdit" class="w-full border border-gray-300 rounded-lg px-3 py-1.5 text-sm shadow-sm">
                                        <p x-show="isEdit" class="text-xs text-gray-500 mt-1">Leave blank to keep current file</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Cover Image</label>
                                        <input type="file" name="cover_image" accept="image/*" class="w-full border border-gray-300 rounded-lg px-3 py-1.5 text-sm shadow-sm">
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tags / Keywords</label>
                                    <input type="text" name="tags" x-model="formData.tags" placeholder="e.g. classic, adventure" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-200 bg-gray-50">
                            <button type="button" @click="open = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 transition-colors">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg shadow-sm hover:bg-blue-700 transition-colors">
                                <span x-text="isEdit ? 'Save Changes' : 'Create Ebook'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- END Drawer --}}

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

    <div class="bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden">
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
                        <span class="bg-blue-50 text-blue-700 text-xs px-2.5 py-0.5 rounded-full font-medium">{{ $ebook->category->name ?? '—' }}</span>
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
                               @click="openEdit({{ $ebook->id }}, {{ Js::from($ebookInlineData) }})"
                               class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                               title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            
                            {{-- Action Dropdown (Three Dots) --}}
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" @click.outside="open = false"
                                        class="p-1.5 text-gray-400 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors focus:outline-none"
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
</div>

<script>
window.ebookDrawer = function() {
    return {
        open: false,
        isEdit: false,
        editId: null,
        formUrl: '{{ route('admin.ebooks.store') }}',

        // Authors multi-select state (lives here to avoid child scope shadowing)
        selected: [],
        allAuthors: {{ Js::from($authors->map(fn($a) => ['id' => $a->id, 'name' => $a->full_name])) }},
        authorSearch: '',
        authorDropdownOpen: false,
        get filteredAuthors() {
            if (!this.authorSearch) return this.allAuthors;
            return this.allAuthors.filter(a => a.name.toLowerCase().includes(this.authorSearch.toLowerCase()));
        },
        toggleAuthor(id) {
            if (this.selected.includes(id)) {
                this.selected = this.selected.filter(i => i !== id);
            } else {
                this.selected.push(id);
            }
        },

        formData: {
            title: '',
            category_id: '',
            isbn: '',
            publisher: '',
            publish_year: '',
            file_type: 'pdf',
            access_level: 'free',
            status: 'active',
            preview_pages: 10,
            tags: ''
        },

        openCreate() {
            this.isEdit = false;
            this.editId = null;
            this.formUrl = '{{ route('admin.ebooks.store') }}';
            this.formData = { title:'', category_id:'', isbn:'', publisher:'', publish_year:'', file_type:'pdf', access_level:'free', status:'active', preview_pages:10, tags:'' };
            this.selected = [];
            this.authorSearch = '';
            this.open = true;
        },

        // Instant — no AJAX, data is passed inline from Blade
        openEdit(id, data) {
            this.isEdit = true;
            this.editId = id;
            this.formUrl = `/admin/ebooks/${id}`;
            this.formData.title        = data.title;
            this.formData.category_id  = String(data.category_id);
            this.formData.isbn         = data.isbn || '';
            this.formData.publisher    = data.publisher || '';
            this.formData.publish_year = data.publish_year ? String(data.publish_year) : '';
            this.formData.file_type    = data.file_type;
            this.formData.access_level = data.access_level;
            this.formData.status       = data.status;
            this.formData.preview_pages= data.preview_pages || 10;
            this.formData.tags         = data.tags || '';
            this.selected              = data.author_ids || [];
            this.authorSearch = '';
            this.open = true;
        }
    };
};

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
@endsection
