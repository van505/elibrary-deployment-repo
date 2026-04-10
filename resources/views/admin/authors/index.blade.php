@extends('layouts.admin')
@section('title', 'Authors')

@section('content')

    <x-admin.filter-bar 
        :action="route('admin.authors.index')" 
        searchPlaceholder="Search author name..."
        :sortable="['created_at' => 'Date Added', 'first_name' => 'First Name', 'last_name' => 'Last Name']"
        :createRoute="route('admin.authors.create')"
        createLabel="Add Author">
    </x-admin.filter-bar>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
            <tr>
                <th class="px-6 py-3">Name</th>
                <th class="px-6 py-3">Nationality</th>
                <th class="px-6 py-3">Ebooks</th>
                <th class="px-6 py-3">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($authors as $author)
            <tr class="border-t border-gray-100 hover:bg-gray-50">
                <td class="px-6 py-4 font-medium text-gray-800">{{ $author->full_name }}</td>
                <td class="px-6 py-4 text-gray-500">{{ $author->nationality ?? '—' }}</td>
                <td class="px-6 py-4">{{ $author->ebooks_count ?? 0 }}</td>
                <td class="px-6 py-4 flex gap-2">
                    <a href="{{ route('admin.authors.show', $author) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">View</a>
                    <a href="{{ route('admin.authors.edit', $author) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm">Edit</a>
                    <form action="{{ route('admin.authors.destroy', $author) }}" method="POST" onsubmit="return confirm('Delete this author?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="px-6 py-8 text-center text-gray-400">No authors found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100">{{ $authors->links() }}</div>
</div>
@endsection
