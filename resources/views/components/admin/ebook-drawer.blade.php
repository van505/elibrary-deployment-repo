@props(['categories', 'authors'])

<div x-data="{
    open: false,
    isEdit: false,
    editId: null,
    formUrl: '{{ route('admin.ebooks.store') }}',
    selected: [],
    allAuthors: {{ Js::from($authors->map(fn($a) => ['id' => $a->id, 'name' => $a->full_name])) }},
    authorSearch: '',
    authorDropdownOpen: false,
    get filteredAuthors() {
        if (!this.authorSearch) return this.allAuthors;
        return this.allAuthors.filter(a => a.name.toLowerCase().includes(this.authorSearch.toLowerCase()));
    },
    toggleAuthor(id) {
        if (this.selected.includes(id)) {
            this.selected = this.selected.filter(i => i !== id);
        } else {
            this.selected.push(id);
        }
    },
    formData: {
        title: '', category_id: '', isbn: '', publisher: '', publish_year: '', file_type: 'pdf', access_level: 'free', status: 'active', preview_pages: 10, tags: ''
    },
    openCreate() {
        this.isEdit = false; this.editId = null; this.formUrl = '{{ route('admin.ebooks.store') }}';
        this.formData = { title:'', category_id:'', isbn:'', publisher:'', publish_year:'', file_type:'pdf', access_level:'free', status:'active', preview_pages:10, tags:'' };
        this.selected = []; this.authorSearch = ''; this.open = true;
    },
    openEdit(id, data) {
        this.isEdit = true; this.editId = id; this.formUrl = `/admin/ebooks/${id}`;
        this.formData.title = data.title; this.formData.category_id = String(data.category_id);
        this.formData.isbn = data.isbn || ''; this.formData.publisher = data.publisher || '';
        this.formData.publish_year = data.publish_year ? String(data.publish_year) : '';
        this.formData.file_type = data.file_type; this.formData.access_level = data.access_level;
        this.formData.status = data.status; this.formData.preview_pages = data.preview_pages || 10;
        this.formData.tags = data.tags || ''; this.selected = data.author_ids || [];
        this.authorSearch = ''; this.open = true;
    }
}" @open-ebook-drawer.window="openCreate()" @open-ebook-drawer-edit.window="openEdit($event.detail.id, $event.detail.data)" @keydown.window.escape="open = false">

    <!-- Backdrop -->
    <div x-show="open" 
         x-transition:enter="ease-in-out duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="ease-in-out duration-300" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity z-40" 
         @click="open = false" style="display: none;"></div>

    <!-- Panel -->
    <div class="fixed inset-y-0 right-0 z-50 flex max-w-full pl-10 pointer-events-none" style="display: none;" x-show="open">
        <div x-show="open" 
             x-transition:enter="transform transition ease-in-out duration-500" 
             x-transition:enter-start="translate-x-full" 
             x-transition:enter-end="translate-x-0" 
             x-transition:leave="transform transition ease-in-out duration-500" 
             x-transition:leave-start="translate-x-0" 
             x-transition:leave-end="translate-x-full" 
             class="w-screen max-w-lg pointer-events-auto">
            
            <form :action="formUrl" method="POST" enctype="multipart/form-data" class="flex flex-col h-full bg-white shadow-2xl">
                @csrf
                <input type="hidden" :name="isEdit ? '_method' : '_noop'" value="PUT">

                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900" x-text="isEdit ? 'Edit Ebook' : 'Add New Ebook'"></h2>
                    <button type="button" @click="open = false" class="text-gray-400 hover:text-gray-500 transition-colors">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="flex-1 px-6 py-6 overflow-y-auto">
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                            <input type="text" name="title" x-model="formData.title" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>

                        {{-- Alpine.js Authors Multi-Select --}}
                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Authors <span class="text-red-500">*</span></label>
                            
                            <!-- Hidden select to submit data -->
                            <select name="author_ids[]" multiple class="hidden">
                                <template x-for="id in selected" :key="id">
                                    <option :value="id" selected></option>
                                </template>
                            </select>
                            
                            <!-- Custom input trigger -->
                            <div @click="authorDropdownOpen = !authorDropdownOpen" @click.outside="authorDropdownOpen = false"
                                 class="w-full border border-gray-300 rounded-lg shadow-sm focus-within:border-blue-500 focus-within:ring-1 focus-within:ring-blue-500 bg-white min-h-[38px] p-1.5 flex flex-wrap gap-1 cursor-text relative">
                                
                                <!-- Selected tags -->
                                <template x-for="id in selected" :key="id">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                        <span x-text="allAuthors.find(a => a.id === id)?.name"></span>
                                        <button type="button" @click.stop="toggleAuthor(id)" class="text-blue-400 hover:text-blue-600">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </span>
                                </template>
                                
                                <input type="text" x-model="authorSearch" placeholder="Select authors..."
                                       class="flex-1 min-w-[100px] border-none shadow-none focus:ring-0 p-0 text-sm bg-transparent"
                                       @keydown.enter.prevent="if(filteredAuthors.length > 0) toggleAuthor(filteredAuthors[0].id)">
                                       
                                <div class="absolute right-2 top-2.5 text-gray-400 pointer-events-none">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                            
                            <!-- Dropdown list -->
                            <div x-show="authorDropdownOpen" style="display: none;"
                                 class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm">
                                <template x-for="author in filteredAuthors" :key="author.id">
                                    <div @click="toggleAuthor(author.id)"
                                         class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-gray-50 flex items-center justify-between"
                                         :class="selected.includes(author.id) ? 'bg-blue-50 text-blue-900' : 'text-gray-900'">
                                        <span class="block truncate" :class="selected.includes(author.id) ? 'font-semibold' : 'font-normal'" x-text="author.name"></span>
                                        <span x-show="selected.includes(author.id)" class="text-blue-600 flex items-center pr-4">
                                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                        </span>
                                    </div>
                                </template>
                                <div x-show="filteredAuthors.length === 0" class="py-2 px-3 text-sm text-gray-500">No authors found</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Category <span class="text-red-500">*</span></label>
                                <select name="category_id" x-model="formData.category_id" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Format <span class="text-red-500">*</span></label>
                                <select name="file_type" x-model="formData.file_type" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    <option value="pdf">PDF</option>
                                    <option value="epub">EPUB</option>
                                    <option value="mp3">MP3 (Audiobook)</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Access Level <span class="text-red-500">*</span></label>
                                <select name="access_level" x-model="formData.access_level" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    <option value="free">Free</option>
                                    <option value="basic">Basic</option>
                                    <option value="premium">Premium</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="status" x-model="formData.status" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ebook File <span class="text-red-500">*</span></label>
                                <input type="file" name="file_path" accept=".pdf,.epub,.mp3" :required="!isEdit" class="w-full border border-gray-300 rounded-lg px-3 py-1.5 text-sm shadow-sm">
                                <p x-show="isEdit" class="text-xs text-gray-500 mt-1">Leave blank to keep current file</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Cover Image</label>
                                <input type="file" name="cover_image" accept="image/*" class="w-full border border-gray-300 rounded-lg px-3 py-1.5 text-sm shadow-sm">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tags / Keywords</label>
                            <input type="text" name="tags" x-model="formData.tags" placeholder="e.g. classic, adventure" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <button type="button" @click="open = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg shadow-sm hover:bg-blue-700 transition-colors">
                        <span x-text="isEdit ? 'Save Changes' : 'Create Ebook'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
