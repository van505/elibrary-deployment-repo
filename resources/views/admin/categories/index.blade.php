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

<div class="h-full" x-data="Object.assign(categoryDrawer(), { viewMode: localStorage.getItem('categoriesView') || 'grid' })" @keydown.window.escape="open = false">
    <div class="space-y-5">

    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-2">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Categories</h1>
            <p class="text-sm text-gray-500 mt-1">Organize your library by topic or genre</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="flex items-center bg-gray-100 rounded-lg p-1 gap-1">
                <button @click="viewMode = 'grid'; localStorage.setItem('categoriesView', 'grid')"
                        :class="viewMode === 'grid' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-500 hover:text-gray-700'"
                        class="p-1.5 rounded-md transition-all duration-150" title="Grid View">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                </button>
                <button @click="viewMode = 'table'; localStorage.setItem('categoriesView', 'table')"
                        :class="viewMode === 'table' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-500 hover:text-gray-700'"
                        class="p-1.5 rounded-md transition-all duration-150" title="Table View">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 6h18M3 14h18M3 18h18"/></svg>
                </button>
            </div>
            <button @click="openCreate()" class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 text-white text-sm font-medium rounded-lg hover:bg-amber-600 transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Category
            </button>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-2">
        <div class="bg-white rounded-xl border border-gray-100 border-t-2 border-t-amber-500 shadow-[0_4px_6px_-1px_rgba(0,0,0,0.07),0_2px_4px_-2px_rgba(0,0,0,0.05)] p-5 flex items-center gap-4 transition-all duration-200 hover:shadow-[0_10px_15px_-3px_rgba(0,0,0,0.08),0_4px_6px_-4px_rgba(0,0,0,0.05)] hover:-translate-y-0.5">
            <div class="w-11 h-11 bg-amber-50 rounded-xl flex items-center justify-center flex-shrink-0 ring-1 ring-amber-100 shadow-sm">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
            </div>
            <div><div class="text-2xl font-bold text-gray-900">{{ $totalCategories }}</div><div class="text-sm text-gray-500">Total Categories</div></div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 border-t-2 border-t-amber-500 shadow-[0_4px_6px_-1px_rgba(0,0,0,0.07),0_2px_4px_-2px_rgba(0,0,0,0.05)] p-5 flex items-center gap-4 transition-all duration-200 hover:shadow-[0_10px_15px_-3px_rgba(0,0,0,0.08),0_4px_6px_-4px_rgba(0,0,0,0.05)] hover:-translate-y-0.5">
            <div class="w-11 h-11 bg-emerald-50 rounded-xl flex items-center justify-center flex-shrink-0 ring-1 ring-emerald-100 shadow-sm">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div><div class="text-2xl font-bold text-gray-900">{{ $categories->total() }}</div><div class="text-sm text-gray-500">Showing</div></div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 border-t-2 border-t-amber-500 shadow-[0_4px_6px_-1px_rgba(0,0,0,0.07),0_2px_4px_-2px_rgba(0,0,0,0.05)] p-5 flex items-center gap-4 transition-all duration-200 hover:shadow-[0_10px_15px_-3px_rgba(0,0,0,0.08),0_4px_6px_-4px_rgba(0,0,0,0.05)] hover:-translate-y-0.5">
            <div class="w-11 h-11 bg-amber-50 rounded-xl flex items-center justify-center flex-shrink-0 ring-1 ring-amber-100 shadow-sm">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/></svg>
            </div>
            <div>
                <div class="text-base font-semibold text-gray-900 truncate max-w-[100px]">{{ $mostUsed?->name ?? '—' }}</div>
                <div class="text-sm text-gray-500">Most Used</div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 border-t-2 border-t-amber-500 shadow-[0_4px_6px_-1px_rgba(0,0,0,0.07),0_2px_4px_-2px_rgba(0,0,0,0.05)] p-5 flex items-center gap-4 transition-all duration-200 hover:shadow-[0_10px_15px_-3px_rgba(0,0,0,0.08),0_4px_6px_-4px_rgba(0,0,0,0.05)] hover:-translate-y-0.5">
            <div class="w-11 h-11 bg-amber-50 rounded-xl flex items-center justify-center flex-shrink-0 ring-1 ring-amber-100 shadow-sm">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253"/></svg>
            </div>
            <div><div class="text-2xl font-bold text-gray-900">{{ $ebooksCategorized }}</div><div class="text-sm text-gray-500">Ebooks Categorized</div></div>
        </div>
    </div>

    {{-- ── Filter Bar ──────────────────────────────────────────── --}}
    <x-admin.filter-bar
        :action="route('admin.categories.index')"
        searchPlaceholder="Search category name..."
        :sortable="['created_at' => 'Date Added', 'name' => 'Name']">
    </x-admin.filter-bar>

    {{-- ── Categories Grid/Table ────────────────────────────────── --}}
    {{-- Grid View --}}
    <div x-show="viewMode === 'grid'" style="display:none;" x-data="{ show: false }" x-init="setTimeout(() => show = true, 50)">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @forelse($categories as $index => $category)
            @php
                $catColor  = $category->color ?? 'blue';
                $dotClass  = $colorMap[$catColor]['dot'] ?? 'bg-blue-400';
                $catHex    = $colorMap[$catColor]['hex'] ?? '#60a5fa';
                $catInline = ['name' => $category->name, 'description' => $category->description ?? '', 'color' => $catColor];
            @endphp
            <div x-show="show"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 style="transition-delay: {{ ($index % 8) * 50 }}ms; display:none;"
                 class="bg-white rounded-xl border border-gray-100 shadow-[0_2px_4px_-1px_rgba(0,0,0,0.06),0_1px_2px_-1px_rgba(0,0,0,0.04)] overflow-hidden hover:-translate-y-1 hover:shadow-[0_12px_20px_-4px_rgba(0,0,0,0.1),0_4px_6px_-4px_rgba(0,0,0,0.06)] hover:border-amber-200 transition-all duration-200 ease-out">
                {{-- Banner --}}
                <div class="h-20 flex items-center justify-center" style="background: linear-gradient(135deg, {{ $catHex }}33, {{ $catHex }}66);">
                    <span class="text-3xl font-black" style="color: {{ $catHex }}">{{ strtoupper(substr($category->name, 0, 1)) }}</span>
                </div>
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
                             class="absolute right-0 top-8 w-36 bg-white rounded-xl shadow-lg border border-gray-100 z-50 py-1">
                            <button @click="openEdit({{ $category->id }}, {{ Js::from($catInline) }}); open = false"
                                    class="w-full flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 transition-colors">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Edit
                            </button>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Archive this category?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Archive
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="pr-6">
                        <h3 class="font-semibold text-gray-900 text-sm">{{ $category->name }}</h3>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $category->ebooks_count ?? 0 }} ebook{{ ($category->ebooks_count ?? 0) !== 1 ? 's' : '' }}</p>
                        @if($category->description)
                            <p class="text-xs text-gray-400 mt-1 line-clamp-2">{{ $category->description }}</p>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-16 text-gray-500">No categories found.</div>
            @endforelse
        </div>
        <div class="mt-4">{{ $categories->links() }}</div>
    </div>

    {{-- Table View --}}
    <div x-show="viewMode === 'table'" style="display:none;" class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
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
        <div class="px-6 py-4 border-t border-gray-100">{{ $categories->links() }}</div>
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
