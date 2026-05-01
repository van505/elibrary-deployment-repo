@extends('layouts.admin')
@section('title', 'Add Ebook')

@section('content')
<div class="mb-6"><a href="{{ route('admin.ebooks.index') }}" class="text-blue-600 hover:underline text-sm">← Back to Ebooks</a></div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-3xl">
    <h2 class="text-xl font-bold text-gray-800 mb-6">Add New Ebook</h2>
    <form action="{{ route('admin.ebooks.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg mb-6">
                <div class="flex items-center gap-2 font-semibold mb-1">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Please fix the following errors:</span>
                </div>
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none @error('title') border-red-400 @enderror">
                @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Authors <span class="text-red-500">*</span></label>
                <p class="text-xs text-gray-400 mb-2">Hold Ctrl/Cmd to select multiple authors</p>
                <select name="author_ids[]" multiple required size="4"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none @error('author_ids') border-red-400 @enderror">
                    @foreach($authors as $author)
                        <option value="{{ $author->id }}" {{ in_array($author->id, (array) old('author_ids', [])) ? 'selected' : '' }}>
                            {{ $author->full_name }}
                        </option>
                    @endforeach
                </select>
                @error('author_ids') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category <span class="text-red-500">*</span></label>
                <select name="category_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">Select Category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">ISBN</label>
                <input type="text" name="isbn" value="{{ old('isbn') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Publisher</label>
                <input type="text" name="publisher" value="{{ old('publisher') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Publish Year</label>
                <input type="number" name="publish_year" value="{{ old('publish_year') }}" min="1000" max="9999" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">File Type <span class="text-red-500">*</span></label>
                <select name="file_type" required class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="pdf"  @selected(old('file_type','pdf') === 'pdf')>PDF</option>
                    <option value="epub" @selected(old('file_type') === 'epub')>EPUB</option>
                    <option value="mp3"  @selected(old('file_type') === 'mp3')>MP3 (Audiobook)</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Access Level <span class="text-red-500">*</span></label>
                <select name="access_level" required class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="free"    @selected(old('access_level','free') === 'free')>Free (everyone)</option>
                    <option value="basic"   @selected(old('access_level') === 'basic')>Basic & above</option>
                    <option value="premium" @selected(old('access_level') === 'premium')>Premium only</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="active"   @selected(old('status', 'active') === 'active')>✅ Active (visible to members)</option>
                    <option value="inactive" @selected(old('status') === 'inactive')>🚫 Inactive (hidden from members)</option>
                </select>
                <p class="text-xs text-gray-400 mt-1">Inactive ebooks are still accessible in admin but hidden from all member views.</p>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Free Preview Pages</label>
                <input type="number" name="preview_pages" value="{{ old('preview_pages', 10) }}"
                       min="0" max="50"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                <p class="text-xs text-gray-400 mt-1">Number of pages non-members can read for free before being prompted to subscribe (0–50).</p>
            </div>

            <div class="md:col-span-2">
                <label class="flex items-center gap-2 cursor-pointer mt-2 mb-4 bg-blue-50/50 p-3 rounded-lg border border-blue-100">
                    <input type="hidden" name="is_featured" value="0">
                    <input type="checkbox" name="is_featured" value="1" class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500" @checked(old('is_featured'))>
                    <div class="flex flex-col">
                        <span class="text-sm font-semibold text-gray-800">Display as Featured (Hero Section)</span>
                        <span class="text-xs text-gray-500">Enable this to actively display this ebook prominently in the Welcome Page visual stack.</span>
                    </div>
                </label>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Tags / Keywords <span class="text-xs text-gray-400 font-normal ml-2">(comma separated)</span></label>
                <input type="text" name="tags" value="{{ old('tags') }}" placeholder="e.g. classic, adventure, fiction" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Ebook File (PDF / EPUB / MP3) <span class="text-red-500">*</span></label>
                <input type="file" name="file_path" accept=".pdf,.epub,.mp3" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                @error('file_path') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Cover Image</label>
                <input type="file" name="cover_image" accept="image/*" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg text-sm font-medium transition-colors">Save Ebook</button>
            <a href="{{ route('admin.ebooks.index') }}" class="border border-gray-300 text-gray-600 hover:bg-gray-50 px-6 py-2.5 rounded-lg text-sm font-medium transition-colors">Cancel</a>
        </div>
    </form>
</div>
@endsection
