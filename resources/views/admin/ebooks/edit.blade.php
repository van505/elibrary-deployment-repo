@extends('layouts.admin')
@section('title', 'Edit Ebook')

@section('content')
<div class="mb-6"><a href="{{ route('admin.ebooks.index') }}" class="text-blue-600 hover:underline text-sm">← Back</a></div>

<div class="bg-white rounded-xl shadow-sm p-6">
    <h2 class="text-xl font-bold text-gray-800 mb-6">Edit Ebook</h2>
    <form action="{{ route('admin.ebooks.update', $ebook) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title', $ebook->title) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                @error('title')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Author <span class="text-red-500">*</span></label>
                <select name="author_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @foreach($authors as $author)
                    <option value="{{ $author->id }}" {{ old('author_id', $ebook->author_id) == $author->id ? 'selected' : '' }}>{{ $author->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category <span class="text-red-500">*</span></label>
                <select name="category_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id', $ebook->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">ISBN</label>
                <input type="text" name="isbn" value="{{ old('isbn', $ebook->isbn) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Publisher</label>
                <input type="text" name="publisher" value="{{ old('publisher', $ebook->publisher) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Publish Year</label>
                <input type="number" name="publish_year" value="{{ old('publish_year', $ebook->publish_year) }}" min="1000" max="9999" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Total Copies <span class="text-red-500">*</span></label>
                <input type="number" name="total_copies" value="{{ old('total_copies', $ebook->total_copies) }}" min="1" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cover Image</label>
                @if($ebook->cover_image)
                    <img src="{{ Storage::url($ebook->cover_image) }}" class="w-16 h-20 object-cover rounded mb-2" onerror="this.src='/images/placeholder.png'">
                @endif
                <input type="file" name="cover_image" accept="image/*" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <p class="text-xs text-gray-400 mt-1">Leave blank to keep current image.</p>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Ebook File</label>
                <input type="file" name="file_path" accept=".pdf,.epub" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <p class="text-xs text-gray-400 mt-1">Leave blank to keep current file.</p>
            </div>
        </div>
        <div class="flex gap-3 mt-8">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">Save Changes</button>
            <a href="{{ route('admin.ebooks.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm">Cancel</a>
        </div>
    </form>
</div>
@endsection
