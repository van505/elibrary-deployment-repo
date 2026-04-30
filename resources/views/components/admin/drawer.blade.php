@props(['title', 'editTitle', 'formAction'])

<template x-teleport="body">
    <div style="position: relative; z-index: 100;" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
        {{-- ── Slide-over Backdrop ──────────────────────────────────────── --}}
        <div x-show="open"
             class="fixed top-0 right-0 w-full h-full z-50 bg-black/50 backdrop-blur-sm transition-opacity"
             @click="open = false"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             style="display: none;"></div>

        {{-- ── Slide-over Panel ─────────────────────────────────────────── --}}
        <div x-show="open"
             class="fixed top-0 right-0 h-full w-full max-w-md z-[51] bg-white shadow-[-10px_0_30px_rgba(0,0,0,0.1)] overflow-y-auto transform transition-transform"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full"
             style="display: none;">

            <form :action="formUrl" method="POST" enctype="multipart/form-data" class="flex flex-col h-full">
                @csrf
                <input type="hidden" name="_method" :value="isEdit ? 'PUT' : 'POST'">

                {{-- Drawer Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-white">
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
                <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-200 bg-gray-50 mt-auto">
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
</template>
