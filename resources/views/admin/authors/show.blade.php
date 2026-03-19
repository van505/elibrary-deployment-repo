@extends('layouts.admin')
@section('title', $author->name)

@section('content')
<div class="mb-6 flex items-center justify-between">
    <a href="{{ route('admin.authors.index') }}" class="text-blue-600 hover:underline text-sm">← Back</a>
    <a href="{{ route('admin.authors.edit', $author) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm">Edit</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-bold text-gray-800 text-lg mb-1">{{ $author->name }}</h3>
        <p class="text-sm text-gray-500 mb-4">{{ $author->nationality ?? 'Unknown nationality' }}</p>
        <p class="text-sm text-gray-600">{{ $author->bio ?? 'No bio available.' }}</p>
    </div>

    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Ebooks by {{ $author->name }}</h3>
        </div>
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3">Title</th>
                    <th class="px-6 py-3">Category</th>
                    <th class="px-6 py-3">Copies</th>
                </tr>
            </thead>
            <tbody>
                @forelse($author->ebooks as $ebook)
                <tr class="border-t border-gray-100 hover:bg-gray-50">
                    <td class="px-6 py-3 font-medium">{{ $ebook->title }}</td>
                    <td class="px-6 py-3 text-gray-500">{{ $ebook->category->name ?? '—' }}</td>
                    <td class="px-6 py-3">{{ $ebook->available_copies }}/{{ $ebook->total_copies }}</td>
                </tr>
                @empty
                <tr><td colspan="3" class="px-6 py-6 text-center text-gray-400">No ebooks yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
