@extends('layouts.admin')
@section('title', 'Authors')

@section('content')
<div class="h-full" x-data="authorDrawer()" @keydown.window.escape="open = false">
    <div class="space-y-5">

        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Authors</h1>
                <p class="text-gray-500 text-sm mt-1">Manage ebook authors and contributors</p>
            </div>
            <button @click="openCreate()"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Author
            </button>
        </div>

        {{-- Filter Bar --}}
        <x-admin.filter-bar
            :action="route('admin.authors.index')"
            searchPlaceholder="Search author name..."
            :sortable="['created_at' => 'Date Added', 'first_name' => 'First Name', 'last_name' => 'Last Name']">
        </x-admin.filter-bar>

        {{-- Authors Table --}}
        <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Author</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nationality</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Ebooks</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($authors as $author)
                    @php
                        $flags = ['Filipino'=>'🇵🇭','Philippine'=>'🇵🇭','American'=>'🇺🇸','British'=>'🇬🇧','Canadian'=>'🇨🇦','Australian'=>'🇦🇺','Japanese'=>'🇯🇵','Korean'=>'🇰🇷','French'=>'🇫🇷','German'=>'🇩🇪','Spanish'=>'🇪🇸','Italian'=>'🇮🇹','Chinese'=>'🇨🇳','Indian'=>'🇮🇳'];
                        $flag = collect($flags)->first(fn($v,$k) => stripos($author->nationality ?? '', $k) !== false) ?? '';
                    @endphp
                    <tr class="hover:bg-gray-50/60 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold flex-shrink-0">
                                    {{ strtoupper(substr($author->first_name ?? '?', 0, 1)) }}
                                </div>
                                <span class="font-semibold text-gray-900">{{ $author->full_name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-sm">{{ $flag }} {{ $author->nationality ?? '—' }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2 py-0.5 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">
                                {{ $author->ebooks_count ?? 0 }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2 justify-end">
                                {{-- View --}}
                                <a href="{{ route('admin.authors.show', $author) }}"
                                   class="p-1.5 text-blue-500 hover:text-blue-700 rounded-lg transition-colors duration-200"
                                   title="View">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>

                                {{-- Edit --}}
                                <button type="button"
                                        @click="openEdit({{ $author->id }}, {{ Js::from(['first_name' => $author->first_name, 'last_name' => $author->last_name, 'nationality' => $author->nationality, 'bio' => $author->bio]) }})"
                                        class="p-1.5 text-indigo-500 hover:text-indigo-700 rounded-lg transition-colors duration-200"
                                        title="Edit">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>

                                {{-- Delete --}}
                                <form action="{{ route('admin.authors.destroy', $author) }}" method="POST"
                                      onsubmit="return confirm('Delete this author?')" class="inline-block">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="p-1.5 text-rose-500 hover:text-rose-700 rounded-lg transition-colors duration-200"
                                            title="Delete">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4">
                        <div class="text-center py-16">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <h3 class="text-gray-900 font-semibold mb-1">No authors yet</h3>
                            <p class="text-gray-500 text-sm mb-4">Get started by adding your first author.</p>
                            <button @click="openCreate()"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Add Author
                            </button>
                        </div>
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
            @if($authors->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">{{ $authors->links() }}</div>
            @endif
        </div>

    </div>{{-- /space-y-5 --}}

    {{-- ── Reusable Drawer Component ──────────────────────────────── --}}
    {{-- Alpine state (open, isEdit, formUrl, formData) is inherited  --}}
    {{-- from the parent x-data="authorDrawer()" scope above.        --}}
    <x-admin.drawer
        title="Add New Author"
        edit-title="Edit Author"
        form-action="{{ route('admin.authors.store') }}">

        {{-- Row 1: First Name + Last Name --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    First Name <span class="text-red-500">*</span>
                </label>
                <input type="text" name="first_name" x-model="formData.first_name" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="e.g. Jane">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Last Name <span class="text-red-500">*</span>
                </label>
                <input type="text" name="last_name" x-model="formData.last_name" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="e.g. Austen">
            </div>
        </div>

        {{-- Row 2: Nationality --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nationality</label>
            <select name="nationality" x-model="formData.nationality"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Select Country...</option>
                <option value="British">🇬🇧 British</option>
                <option value="American">🇺🇸 American</option>
                <option value="French">🇫🇷 French</option>
                <option value="Japanese">🇯🇵 Japanese</option>
                <option value="Filipino">🇵🇭 Filipino</option>
                <option value="Canadian">🇨🇦 Canadian</option>
                <option value="Australian">🇦🇺 Australian</option>
                <option value="Korean">🇰🇷 Korean</option>
                <option value="German">🇩🇪 German</option>
                <option value="Spanish">🇪🇸 Spanish</option>
                <option value="Italian">🇮🇹 Italian</option>
                <option value="Chinese">🇨🇳 Chinese</option>
                <option value="Indian">🇮🇳 Indian</option>
            </select>
        </div>

        {{-- Row 3: Biography --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Biography</label>
            <textarea name="bio" x-model="formData.bio" rows="4"
                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                      placeholder="A brief description of the author..."></textarea>
        </div>

        {{-- Row 4: Author Photo --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Author Photo</label>
            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors bg-gray-50"
                 x-data="{ dragging: false }"
                 @dragover.prevent="dragging = true"
                 @dragleave.prevent="dragging = false"
                 @drop.prevent="dragging = false; $refs.photoInput.files = $event.dataTransfer.files"
                 :class="{ 'border-blue-500 bg-blue-50': dragging }">
                <div class="space-y-1 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                              stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <div class="flex text-sm text-gray-600 justify-center">
                        <label class="relative cursor-pointer rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                            <span>Upload a photo</span>
                            <input x-ref="photoInput" type="file" name="photo" class="sr-only" accept="image/*">
                        </label>
                        <p class="pl-1">or drag and drop</p>
                    </div>
                    <p class="text-xs text-gray-500">PNG, JPG up to 2MB</p>
                </div>
            </div>
        </div>

    </x-admin.drawer>

</div>{{-- /h-full x-data --}}

<script>
window.authorDrawer = function() {
    return {
        open: false,
        isEdit: false,
        editId: null,
        formUrl: '{{ route('admin.authors.store') }}',

        formData: {
            first_name: '',
            last_name: '',
            nationality: '',
            bio: '',
        },

        openCreate() {
            this.isEdit  = false;
            this.editId  = null;
            this.formUrl = '{{ route('admin.authors.store') }}';
            this.formData = { first_name: '', last_name: '', nationality: '', bio: '' };
            this.open = true;
        },

        openEdit(id, data) {
            this.isEdit  = true;
            this.editId  = id;
            this.formUrl = `/admin/authors/${id}`;
            this.formData.first_name  = data.first_name  || '';
            this.formData.last_name   = data.last_name   || '';
            this.formData.nationality = data.nationality || '';
            this.formData.bio         = data.bio         || '';
            this.open = true;
        },
    };
};
</script>
@endsection
