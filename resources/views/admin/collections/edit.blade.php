@extends('layouts.admin')

@section('header')
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.collections.index') }}" class="text-gray-400 hover:text-gray-600 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Edit Collection</h2>
            <p class="text-sm text-gray-500">{{ $collection->name }}</p>
        </div>
    </div>
@endsection

@section('content')
<div class="max-w-2xl bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <form action="{{ route('admin.collections.update', $collection->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Collection Name *</label>
                <input type="text" name="name" value="{{ old('name', $collection->name) }}" required
                       class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="4" 
                          class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description', $collection->description) }}</textarea>
                @error('description') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Cover Image</label>
                @if($collection->cover_image)
                    <div class="mb-3">
                        <img src="{{ Storage::url($collection->cover_image) }}" class="w-24 h-32 object-cover rounded shadow-sm border border-gray-200">
                    </div>
                @endif
                <input type="file" name="cover_image" accept="image/*"
                       class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm bg-gray-50">
                <p class="mt-1 text-xs text-gray-500">Optional. Uploading a new image will replace the current one.</p>
                @error('cover_image') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="flex items-center gap-3">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $collection->is_active) ? 'checked' : '' }}
                           class="w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                    <div>
                        <span class="block text-sm font-medium text-gray-900">Active</span>
                        <span class="block text-xs text-gray-500">Visible to members</span>
                    </div>
                </label>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end gap-3">
            <a href="{{ route('admin.collections.index') }}" class="px-5 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition">Cancel</a>
            <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition shadow-sm">Save Changes</button>
        </div>
    </form>
</div>
@endsection
