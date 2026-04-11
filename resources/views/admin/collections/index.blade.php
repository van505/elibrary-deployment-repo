@extends('layouts.admin')

@section('title', 'Collections (Series)')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <p class="text-sm text-gray-500">Group related ebooks into sequential series or thematic collections.</p>
    </div>
    <a href="{{ route('admin.collections.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium whitespace-nowrap">
        + Create Collection
    </a>
</div>

{{-- Filters & Search --}}
<div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
    <form action="{{ route('admin.collections.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
        
        <div class="flex-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search collections..." 
                   class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        
        <div class="w-full md:w-40">
            <select name="status" class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="w-full md:w-48">
            <select name="sort" class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" onchange="this.form.submit()">
                <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Newest First</option>
                <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Name (A-Z)</option>
                <option value="name_desc" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>Name (Z-A)</option>
            </select>
        </div>
        
        <div class="flex gap-2">
            <button type="submit" class="px-5 py-2 bg-gray-900 text-white rounded-lg font-medium text-sm hover:bg-gray-800 transition">Filter</button>
            @if(request()->hasAny(['search', 'status', 'sort']))
                <a href="{{ route('admin.collections.index') }}" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg font-medium text-sm hover:bg-gray-200 transition">Clear</a>
            @endif
        </div>
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 uppercase text-xs font-semibold text-gray-500 tracking-wider">
                    <th class="px-6 py-4">Collection Info</th>
                    <th class="px-6 py-4">Books</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($collections as $collection)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                @if($collection->cover_image)
                                    <img src="{{ Storage::url($collection->cover_image) }}" class="w-12 h-16 object-cover rounded shadow-sm">
                                @else
                                    <div class="w-12 h-16 bg-gray-200 rounded flex items-center justify-center shadow-sm">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                    </div>
                                @endif
                                <div>
                                    <div class="font-bold text-gray-900">{{ $collection->name }}</div>
                                    <div class="text-xs text-gray-500 truncate max-w-[200px]">{{ $collection->description ?: 'No description' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-mono text-sm text-gray-600">
                            <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-0.5 rounded-full">{{ $collection->ebooks_count }} items</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($collection->is_active)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.collections.show', $collection->id) }}" class="text-blue-500 hover:text-blue-700" title="Manage Books">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </a>
                                <a href="{{ route('admin.collections.edit', $collection->id) }}" class="text-gray-400 hover:text-blue-600 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('admin.collections.destroy', $collection->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this collection?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-600 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                            No collections found. Create a collection to start grouping ebooks.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($collections->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $collections->links() }}
        </div>
    @endif
</div>
@endsection
