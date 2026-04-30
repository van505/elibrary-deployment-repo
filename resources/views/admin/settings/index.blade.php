@extends('layouts.admin')
@section('title', 'System Settings Hub')

@section('content')
<div class="space-y-6" x-data="{ activeTab: '{{ request('activeTab', 'general') }}' }" x-cloak>

    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">System Settings Hub</h1>
            <p class="text-gray-500 text-sm mt-1">Manage all your application configurations in one place</p>
        </div>
        <button form="settingsForm" type="submit" x-show="activeTab === 'general'"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Save General Settings
        </button>
    </div>

    {{-- Main Layout: 2-Column Vertical Tabs --}}
    <div class="flex flex-col md:flex-row gap-6">

        {{-- Left Column: Navigation Sidebar --}}
        <div class="w-full md:w-64 shrink-0 flex flex-col gap-2">
            <button @click="activeTab = 'general'; window.history.replaceState(null, null, '?activeTab=general')" 
                    :class="activeTab === 'general' ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'"
                    class="text-left px-4 py-2.5 text-sm rounded-lg transition-colors flex items-center gap-3">
                <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                General Settings
            </button>
            <button @click="activeTab = 'activity'; window.history.replaceState(null, null, '?activeTab=activity')" 
                    :class="activeTab === 'activity' ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'"
                    class="text-left px-4 py-2.5 text-sm rounded-lg transition-colors flex items-center gap-3">
                <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 01-2-2h2a2 2 0 012 2"></path></svg>
                Activity Logs
            </button>
            <button @click="activeTab = 'archive'; window.history.replaceState(null, null, '?activeTab=archive')" 
                    :class="activeTab === 'archive' ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'"
                    class="text-left px-4 py-2.5 text-sm rounded-lg transition-colors flex items-center gap-3">
                <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                Data Archive
            </button>

            <div class="border-t border-gray-100 my-2"></div>

            {{-- Subscription Plans Quick Link --}}
            <a href="{{ route('admin.subscription-plans.index') }}"
               class="group flex items-center justify-between px-4 py-2.5 text-sm rounded-lg transition-all duration-200 text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 border border-transparent hover:border-indigo-100">
                <span class="flex items-center gap-3">
                    <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    Subscription Plans
                </span>
                <svg class="w-4 h-4 text-gray-400 group-hover:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        {{-- Right Column: Main Content Area --}}
        <div class="flex-1 bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden min-h-[500px]">
            
            {{-- Tab 1: General Settings --}}
            <div x-show="activeTab === 'general'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" class="p-6 md:p-8">
                <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" id="settingsForm" class="space-y-10 max-w-2xl">
                    @csrf @method('PUT')
                    
                    {{-- Site Identity Section --}}
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-5 border-b border-gray-100 pb-2">Site Identity</h3>
                        <div class="space-y-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Site Name</label>
                                <input type="text" name="site_name"
                                       value="{{ old('site_name', $settings->get('site_name')?->value ?? 'ELibrary') }}"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-shadow">
                                <p class="text-xs text-gray-400 mt-1.5">{{ $settings->get('site_name')?->description ?? 'The name displayed across the application.' }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Site Logo</label>
                                <div class="flex items-start gap-4">
                                    <div class="w-16 h-16 rounded-xl border border-gray-200 bg-gray-50 flex items-center justify-center overflow-hidden flex-shrink-0">
                                        @if($settings->get('site_logo')?->value)
                                            <img src="{{ asset('storage/' . $settings->get('site_logo')->value) }}"
                                                 alt="Current Logo" id="logoPreview" class="w-full h-full object-contain p-1">
                                        @else
                                            <svg id="logoPlaceholder" class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <img id="logoPreview" class="hidden w-full h-full object-contain p-1" alt="Logo preview">
                                        @endif
                                    </div>
                                    <div>
                                        <label for="siteLogoInput"
                                               class="inline-flex items-center gap-2 cursor-pointer px-4 py-2 border border-gray-200 text-sm text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                            </svg>
                                            Upload New Logo
                                        </label>
                                        <input type="file" id="siteLogoInput" name="site_logo" accept="image/*" class="hidden"
                                               onchange="previewLogo(this)">
                                        <p class="text-xs text-gray-400 mt-1.5">{{ $settings->get('site_logo')?->description ?? 'PNG, JPG, GIF up to 2MB.' }}</p>
                                        <p id="logoFilename" class="text-xs text-indigo-600 mt-1 hidden font-medium"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Access Control Section --}}
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-5 border-b border-gray-100 pb-2">Access Control & Subscriptions</h3>
                        <div class="space-y-6">
                            
                            {{-- Max Ebook Access Per Day --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Max Ebook Access Per Day</label>
                                <input type="number" name="max_ebook_access_per_day" min="1"
                                       value="{{ old('max_ebook_access_per_day', $settings->get('max_ebook_access_per_day')?->value ?? 10) }}"
                                       class="w-32 border border-gray-300 rounded-lg px-4 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                                <p class="text-xs text-gray-400 mt-1.5">{{ $settings->get('max_ebook_access_per_day')?->description ?? 'How many ebooks a basic member can access per day.' }}</p>
                            </div>

                            {{-- Default Subscription Plan --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Default Subscription Plan for New Members</label>
                                <select name="default_subscription_plan"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white">
                                    <option value="">— None (no automatic plan) —</option>
                                    @foreach($plans as $plan)
                                        <option value="{{ $plan->id }}"
                                                {{ ($settings->get('default_subscription_plan')?->value == $plan->id) ? 'selected' : '' }}>
                                            {{ $plan->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-400 mt-1.5">{{ $settings->get('default_subscription_plan')?->description ?? 'Automatically assign this plan when a new member registers.' }}</p>
                            </div>

                            {{-- Toggle Switches --}}
                            <div class="space-y-4 pt-2">
                                {{-- Allow Registration --}}
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">Allow Member Self-Registration</p>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $settings->get('allow_registration')?->description ?? 'Allow new members to register themselves.' }}</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="allow_registration" value="1" class="sr-only peer"
                                               {{ ($settings->get('allow_registration')?->value ?? '1') == '1' ? 'checked' : '' }}>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                    </label>
                                </div>

                                {{-- Maintenance Mode --}}
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">Maintenance Mode</p>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $settings->get('maintenance_mode')?->description ?? 'When enabled, only admins can access the site.' }}</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="maintenance_mode" value="1" class="sr-only peer"
                                               {{ ($settings->get('maintenance_mode')?->value ?? '0') == '1' ? 'checked' : '' }}>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-500"></div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Tab 2: Activity Logs --}}
            <div x-show="activeTab === 'activity'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" class="p-6 md:p-8" style="display: none;">
                <h3 class="text-lg font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">Activity Logs</h3>
                @include('admin.settings.partials.activity-logs')
            </div>

            {{-- Tab 3: Data Archive --}}
            <div x-show="activeTab === 'archive'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" class="p-6 md:p-8" style="display: none;">
                <h3 class="text-lg font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">Data Archive</h3>
                @include('admin.settings.partials.archive')
            </div>
            
        </div>
    </div>
</div>

@push('scripts')
<style>
    [x-cloak] { display: none !important; }
</style>
<script>
function previewLogo(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const preview = document.getElementById('logoPreview');
            const placeholder = document.getElementById('logoPlaceholder');
            const filename = document.getElementById('logoFilename');
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            if (placeholder) placeholder.classList.add('hidden');
            filename.textContent = '✓ ' + input.files[0].name;
            filename.classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
@endsection
