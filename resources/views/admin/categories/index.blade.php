@extends('layouts.admin')
@section('title', 'Categories')

@section('content')
@php
    $colorMap = [
        'red'    => ['dot' => 'bg-red-400',    'hex' => '#f87171'],
        'orange' => ['dot' => 'bg-orange-400', 'hex' => '#fb923c'],
        'yellow' => ['dot' => 'bg-yellow-400', 'hex' => '#facc15'],
        'green'  => ['dot' => 'bg-emerald-400','hex' => '#34d399'],
        'blue'   => ['dot' => 'bg-blue-400',   'hex' => '#60a5fa'],
        'indigo' => ['dot' => 'bg-indigo-400', 'hex' => '#818cf8'],
        'purple' => ['dot' => 'bg-purple-400', 'hex' => '#c084fc'],
        'pink'   => ['dot' => 'bg-pink-400',   'hex' => '#f472b6'],
        'slate'  => ['dot' => 'bg-slate-400',  'hex' => '#94a3b8'],
    ];
@endphp

<div class="h-full" x-data="categoryDrawer()" @keydown.window.escape="open = false">
    <div class="space-y-5">

    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Categories</h1>
            <p class="text-gray-500 text-sm mt-1">Organize your library by topic or genre</p>
        </div>
        <button @click="openCreate()"
                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Category
        </button>
    </div>


    {{-- ── Filter Bar ──────────────────────────────────────────── --}}
    <x-admin.filter-bar
        :action="route('admin.categories.index')"
        searchPlaceholder="Search category name..."
        :sortable="['created_at' => 'Date Added', 'name' => 'Name']">
    </x-admin.filter-bar>

    {{-- ── Categories Table ────────────────────────────────────── --}}
    <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Slug</th>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Books</th>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Description</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($categories as $category)
                    @php
                        $catColor  = $category->color ?? 'blue';
                        $dotClass  = $colorMap[$catColor]['dot'] ?? 'bg-blue-400';
                        $catInline = [
                            'name'        => $category->name,
                            'description' => $category->description ?? '',
                            'color'       => $catColor,
                        ];
                    @endphp
                    <tr class="hover:bg-gray-50/60 transition-colors">
                        {{-- Name --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2.5">
                                <span class="w-2.5 h-2.5 rounded-full flex-shrink-0 {{ $dotClass }}"></span>
                                <span class="font-semibold text-gray-900">{{ $category->name }}</span>
                            </div>
                        </td>
                        {{-- Slug --}}
                        <td class="px-6 py-4">
                            <code class="font-mono text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded">{{ $category->slug }}</code>
                        </td>
                        {{-- Book Count --}}
                        <td class="px-6 py-4">
                            <span class="text-xs font-medium text-gray-500">{{ $category->ebooks_count ?? $category->ebooks()->count() }} books</span>
                        </td>
                        {{-- Description --}}
                        <td class="px-6 py-4 text-gray-400 text-sm truncate max-w-[180px]">
                            {{ $category->description ?? '—' }}
                        </td>
                        {{-- Actions --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2 justify-end">
                                {{-- Edit pencil --}}
                                <button type="button"
                                        @click="openEdit({{ $category->id }}, {{ Js::from($catInline) }})"
                                        class="p-1.5 text-indigo-500 hover:text-indigo-700 rounded-lg transition-colors duration-200"
                                        title="Edit">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                {{-- Delete --}}
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                                      onsubmit="return confirm('Archive this category?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="p-1.5 text-rose-500 hover:text-rose-700 rounded-lg transition-colors duration-200"
                                            title="Delete">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">
                        <div class="text-center py-16">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                            </div>
                            <h3 class="text-gray-900 font-semibold mb-1">No categories yet</h3>
                            <p class="text-gray-500 text-sm mb-4">Get started by adding your first category.</p>
                            <button @click="openCreate()"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Add Category
                            </button>
                        </div>
                    </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>{{-- /space-y-5 --}}

    {{-- ── Reusable Drawer Component ──────────────────────────────── --}}
    <x-admin.drawer
        title="Add Category"
        edit-title="Edit Category"
        form-action="{{ route('admin.categories.store') }}">

        {{-- Row 1: Name + Color side-by-side --}}
        <div class="grid grid-cols-2 gap-4">
            {{-- Name --}}
            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" x-model="formData.name" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <p class="text-xs text-gray-400 mt-1">Auto-generates slug.</p>
            </div>

            {{-- Color Swatches --}}
            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Color</label>
                <input type="hidden" name="color" x-model="formData.color">
                <div class="flex flex-wrap gap-2">
                    @foreach($colorMap as $colorName => $colorVal)
                        <button type="button"
                                @click="formData.color = '{{ $colorName }}'"
                                title="{{ ucfirst($colorName) }}"
                                class="w-6 h-6 rounded-full transition-all duration-150 focus:outline-none flex items-center justify-center"
                                :class="formData.color === '{{ $colorName }}'
                                    ? 'ring-2 ring-offset-1 ring-gray-700 scale-110'
                                    : 'hover:scale-110'"
                                style="background-color: {{ $colorVal['hex'] }}">
                            <svg x-show="formData.color === '{{ $colorName }}'"
                                 class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </button>
                    @endforeach
                </div>
                <p class="text-xs text-gray-400 mt-1 capitalize" x-text="formData.color"></p>
            </div>
        </div>

        {{-- Row 2: Description full-width --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="description" x-model="formData.description" rows="3"
                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                      placeholder="A short description of this category..."></textarea>
        </div>

    </x-admin.drawer>

</div>{{-- /h-full x-data --}}

<script>
window.categoryDrawer = function() {
    return {
        open: false,
        isEdit: false,
        editId: null,
        formUrl: '{{ route('admin.categories.store') }}',

        formData: {
            name: '',
            description: '',
            color: 'blue',
        },

        openCreate() {
            this.isEdit  = false;
            this.editId  = null;
            this.formUrl = '{{ route('admin.categories.store') }}';
            this.formData = { name: '', description: '', color: 'blue' };
            this.open = true;
        },

        openEdit(id, data) {
            this.isEdit  = true;
            this.editId  = id;
            this.formUrl = `/admin/categories/${id}`;
            this.formData.name        = data.name;
            this.formData.description = data.description || '';
            this.formData.color       = data.color || 'blue';
            this.open = true;
        }
    };
};
</script>
@endsection
