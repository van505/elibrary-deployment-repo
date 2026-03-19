@extends('layouts.admin')
@section('title', 'Add Category')

@section('content')
<div class="mb-6"><a href="{{ route('admin.categories.index') }}" class="text-blue-600 hover:underline text-sm">← Back</a></div>

<div class="bg-white rounded-xl shadow-sm p-6 max-w-lg">
    <h2 class="text-xl font-bold text-gray-800 mb-6">Add New Category</h2>
    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <p class="text-xs text-gray-400 mt-1">Slug will be auto-generated from the name.</p>
                @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
                @error('description')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
        </div>
        <div class="flex gap-3 mt-6">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">Save Category</button>
            <a href="{{ route('admin.categories.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm">Cancel</a>
        </div>
    </form>
</div>
@endsection
