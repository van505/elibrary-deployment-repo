@props(['title', 'editTitle', 'formAction'])

{{-- ── Slide-over Backdrop ──────────────────────────────────────── --}}
<div x-show="open"
     class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity z-[90]"
     @click="open = false"
     x-transition:enter="ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     style="display: none;"></div>

{{-- ── Slide-over Panel ─────────────────────────────────────────── --}}
<div class="fixed inset-y-0 right-0 z-[100] flex max-w-full pl-10" x-show="open" style="display: none;">
    <div x-show="open"
         x-transition:enter="transform transition ease-in-out duration-300 sm:duration-500"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transform transition ease-in-out duration-300 sm:duration-500"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         class="w-screen max-w-md pointer-events-auto">

        <form :action="formUrl" method="POST" enctype="multipart/form-data"
              class="flex flex-col h-full bg-white shadow-2xl">
            @csrf
            <input type="hidden" name="_method" :value="isEdit ? 'PUT' : 'POST'">

            {{-- Drawer Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900"
                    x-text="isEdit ? '{{ $editTitle }}' : '{{ $title }}'"></h2>
                <button type="button" @click="open = false"
                        class="p-1 text-gray-400 hover:text-gray-600 rounded-md hover:bg-gray-100 transition-all duration-200"
                        aria-label="Close drawer">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Drawer Body: form fields injected by each page --}}
            <div class="flex-1 px-6 py-6 overflow-y-auto space-y-5">
                {{ $slot }}
            </div>

            {{-- Drawer Footer --}}
            <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-200 bg-gray-50">
                <button type="button" @click="open = false"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 transition-all duration-200">
                    Cancel
                </button>
                <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg shadow-sm hover:bg-blue-700 transition-all duration-200">
                    <span x-text="isEdit ? 'Save Changes' : '{{ $title }}'"></span>
                </button>
            </div>
        </form>
    </div>
</div>
