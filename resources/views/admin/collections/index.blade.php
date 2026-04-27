@extends('layouts.admin')
@section('title', 'Collections')

@section('content')
<div class="h-full" x-data="collectionDrawer()" @keydown.window.escape="open = false">
    <div class="space-y-5">

    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Collections</h1>
            <p class="text-gray-500 text-sm mt-1">Group related ebooks into sequential series or thematic collections</p>
        </div>
        <button @click="openCreate()"
                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Create Collection
        </button>
    </div>

    {{-- ── Slide-over Backdrop ─────────────────────────────────── --}}
    <div x-show="open"
         x-transition:enter="ease-in-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in-out duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/50 z-[90]"
         @click="open = false" style="display: none;"></div>

    {{-- ── Slide-over Panel ────────────────────────────────────── --}}
    <div class="fixed inset-y-0 right-0 z-[100] flex max-w-full pl-10" x-show="open" style="display: none;">
        <div x-show="open"
             x-transition:enter="transform transition ease-in-out duration-300"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transform transition ease-in-out duration-300"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full"
             class="w-screen max-w-md pointer-events-auto">

            <form :action="formUrl" method="POST" enctype="multipart/form-data" class="flex flex-col h-full bg-white shadow-2xl">
                @csrf
                <input type="hidden" :name="isEdit ? '_method' : '_noop'" value="PUT">

                {{-- Drawer Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900" x-text="isEdit ? 'Edit Collection' : 'New Collection'"></h2>
                    <button type="button" @click="open = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Drawer Body --}}
                <div class="flex-1 px-6 py-6 overflow-y-auto space-y-5">

                    {{-- Name --}}
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
                             @drop.prevent="dragging = false"
                             :class="dragging ? 'border-indigo-500 bg-indigo-50' : ''">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-10 w-10 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <label for="cover_image_input" class="relative cursor-pointer rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                        <span>Upload a file</span>
                                        <input id="cover_image_input" name="cover_image" type="file" accept="image/*" class="sr-only">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB (Ratio 2:3)</p>
                            </div>
                        </div>
                    </div>

                    {{-- Status toggle --}}
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <label class="flex items-center gap-3 cursor-pointer w-full">
                            <input type="checkbox" name="is_active" value="1"
                                   x-model="formData.is_active"
                                   class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                            <div>
                                <span class="block text-sm font-medium text-gray-900">Active</span>
                                <span class="block text-xs text-gray-500">Visible to members on the platform</span>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Drawer Footer --}}
                <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <button type="button" @click="open = false"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg shadow-sm hover:bg-blue-700 transition-colors">
                        <span x-text="isEdit ? 'Save Changes' : 'Create Collection'"></span>
                    </button>
                </div>
            </form>
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

    {{-- ── Collections Table ───────────────────────────────────── --}}
    <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Collection Info</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Books</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Created</th>
                    <th class="px-4 py-3"></th>
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
