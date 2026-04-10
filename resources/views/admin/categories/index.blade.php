@extends('layouts.admin')
@section('title', 'Categories')

@section('content')

    <x-admin.filter-bar 
        :action="route('admin.categories.index')" 
        searchPlaceholder="Search category name..."
        :sortable="['created_at' => 'Date Added', 'name' => 'Name']"
        :createRoute="route('admin.categories.create')"
        createLabel="Add Category">
    </x-admin.filter-bar>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
            <tr>
                <th class="px-6 py-3">Name</th>
                <th class="px-6 py-3">Slug</th>
                <th class="px-6 py-3">Description</th>
                <th class="px-6 py-3">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $category)
            <tr class="border-t border-gray-100 hover:bg-gray-50">
                <td class="px-6 py-4 font-medium text-gray-800">{{ $category->name }}</td>
                <td class="px-6 py-4"><code class="bg-gray-100 px-2 py-0.5 rounded text-xs">{{ $category->slug }}</code></td>
                <td class="px-6 py-4 text-gray-500 truncate max-w-xs">{{ $category->description ?? '—' }}</td>
                <td class="px-6 py-4 flex gap-2">
                    <a href="{{ route('admin.categories.edit', $category) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm">Edit</a>
                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete this category?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="px-6 py-8 text-center text-gray-400">No categories found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100">{{ $categories->links() }}</div>
</div>
@endsection
