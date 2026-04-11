@extends('layouts.admin')
@section('title', 'Edit Announcement')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-xl font-bold text-gray-800">Edit Announcement</h2>
        <a href="{{ route('admin.announcements.index') }}" class="text-sm text-gray-500 hover:text-blue-600 transition">Cancel</a>
    </div>

    <form action="{{ route('admin.announcements.update', $announcement) }}" method="POST" class="space-y-5">
        @csrf @method('PUT')

        {{-- Type --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Announcement Type</label>
            <select name="type" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                <option value="info" {{ old('type', $announcement->type) === 'info' ? 'selected' : '' }}>Info (Blue)</option>
                <option value="warning" {{ old('type', $announcement->type) === 'warning' ? 'selected' : '' }}>Warning (Yellow)</option>
                <option value="success" {{ old('type', $announcement->type) === 'success' ? 'selected' : '' }}>Success (Green)</option>
                <option value="danger" {{ old('type', $announcement->type) === 'danger' ? 'selected' : '' }}>Danger (Red)</option>
            </select>
            @error('type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Title --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
            <input type="text" name="title" value="{{ old('title', $announcement->title) }}" required
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
            @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Message --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
            <textarea name="message" rows="4" required
                      class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">{{ old('message', $announcement->message) }}</textarea>
            @error('message') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Start & End Dates --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Starts At (Optional)</label>
                <input type="datetime-local" name="starts_at" value="{{ old('starts_at', $announcement->starts_at?->format('Y-m-d\TH:i')) }}"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                @error('starts_at') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ends At (Optional)</label>
                <input type="datetime-local" name="ends_at" value="{{ old('ends_at', $announcement->ends_at?->format('Y-m-d\TH:i')) }}"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                @error('ends_at') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Is Active --}}
        <div class="flex items-center pt-2">
            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $announcement->is_active) ? 'checked' : '' }}
                   class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500 border-gray-300">
            <label for="is_active" class="ml-2 block text-sm font-medium text-gray-700">Set Active</label>
            @error('is_active') <p class="text-red-500 text-xs mt-1 ml-2">{{ $message }}</p> @enderror
        </div>

        <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2.5 rounded-lg shadow-sm transition">
                Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
