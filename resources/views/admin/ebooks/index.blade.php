@extends('layouts.admin')
@section('title', 'Ebooks')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Ebooks</h2>
    <a href="{{ route('admin.ebooks.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">+ Add Ebook</a>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
            <tr>
                <th class="px-6 py-3">Cover</th>
                <th class="px-6 py-3">Title</th>
                <th class="px-6 py-3">Author</th>
                <th class="px-6 py-3">Category</th>
                <th class="px-6 py-3">Copies</th>
                <th class="px-6 py-3">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ebooks as $ebook)
            <tr class="border-t border-gray-100 hover:bg-gray-50">
                <td class="px-6 py-3">
                    @if($ebook->cover_image)
                        <img src="{{ Storage::url($ebook->cover_image) }}" class="w-10 h-14 object-cover rounded" onerror="this.src='/images/placeholder.png'">
                    @else
                        <img src="/images/placeholder.png" class="w-10 h-14 object-cover rounded">
                    @endif
                </td>
                <td class="px-6 py-3 font-medium text-gray-800 max-w-xs">{{ $ebook->title }}</td>
                <td class="px-6 py-3 text-gray-500">{{ $ebook->author->name }}</td>
                <td class="px-6 py-3"><span class="bg-blue-100 text-blue-700 text-xs px-2 py-0.5 rounded-full">{{ $ebook->category->name }}</span></td>
                <td class="px-6 py-3">
                    <span class="{{ $ebook->available_copies > 0 ? 'text-green-600' : 'text-red-600' }} font-medium">{{ $ebook->available_copies }}</span>/{{ $ebook->total_copies }}
                </td>
                <td class="px-6 py-3 flex gap-2">
                    <a href="{{ route('admin.ebooks.show', $ebook) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">View</a>
                    <a href="{{ route('admin.ebooks.edit', $ebook) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm">Edit</a>
                    <form action="{{ route('admin.ebooks.destroy', $ebook) }}" method="POST" onsubmit="return confirm('Delete this ebook?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-6 py-8 text-center text-gray-400">No ebooks found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100">{{ $ebooks->links() }}</div>
</div>
@endsection
