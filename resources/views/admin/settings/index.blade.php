@extends('layouts.admin')
@section('title', 'System Settings')

@section('content')
<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" id="settingsForm">
    @csrf @method('PUT')

    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">System Settings</h1>
            <p class="text-gray-500 text-sm mt-1">Manage your application preferences and configuration</p>
        </div>
        <button type="submit"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Save All Settings
        </button>
    </div>

    <div class="space-y-6 max-w-3xl">

        {{-- Card 1: Site Identity --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100">
                <div class="w-9 h-9 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 text-sm">Site Identity</h3>
                    <p class="text-xs text-gray-400">Your library's name and visual branding</p>
                </div>
            </div>
            <div class="p-6 space-y-5">
                {{-- Site Name --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Site Name</label>
                    <input type="text" name="site_name"
                           value="{{ old('site_name', $settings->get('site_name')?->value ?? 'ELibrary') }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow">
                    <p class="text-xs text-gray-400 mt-1.5">{{ $settings->get('site_name')?->description ?? 'The name displayed across the application.' }}</p>
                </div>

                {{-- Site Logo --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Site Logo</label>
                    <div class="flex items-start gap-4">
                        {{-- Preview --}}
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
                        {{-- Upload button --}}
                        <div>
                            <label for="siteLogoInput"
                                   class="inline-flex items-center gap-2 cursor-pointer px-4 py-2 border border-gray-200 text-sm text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                                Upload New Logo
                            </label>
                            <input type="file" id="siteLogoInput" name="site_logo" accept="image/*" class="hidden"
                                   onchange="previewLogo(this)">
                            <p class="text-xs text-gray-400 mt-1.5">{{ $settings->get('site_logo')?->description ?? 'PNG, JPG, GIF up to 2MB.' }}</p>
                            <p id="logoFilename" class="text-xs text-blue-600 mt-1 hidden font-medium"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2: Access Control --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100">
                <div class="w-9 h-9 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 text-sm">Access Control</h3>
                    <p class="text-xs text-gray-400">Control member access limits and registration</p>
                </div>
            </div>
            <div class="p-6 space-y-5">
                {{-- Max Ebook Access Per Day --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Max Ebook Access Per Day (per member)</label>
                    <div class="flex items-center gap-0 w-36">
                        <button type="button"
                                onclick="document.getElementById('maxEbookInput').stepDown()"
                                class="w-10 h-10 flex items-center justify-center border border-gray-200 rounded-l-xl bg-gray-50 hover:bg-gray-100 text-gray-600 font-bold text-lg transition-colors">−</button>
                        <input type="number" name="max_ebook_access_per_day" id="maxEbookInput" min="1"
                               value="{{ old('max_ebook_access_per_day', $settings->get('max_ebook_access_per_day')?->value ?? 10) }}"
                               class="w-full h-10 border-y border-gray-200 px-3 text-sm text-center focus:ring-2 focus:ring-blue-500 outline-none appearance-none">
                        <button type="button"
                                onclick="document.getElementById('maxEbookInput').stepUp()"
                                class="w-10 h-10 flex items-center justify-center border border-gray-200 rounded-r-xl bg-gray-50 hover:bg-gray-100 text-gray-600 font-bold text-lg transition-colors">+</button>
                    </div>
                    <p class="text-xs text-gray-400 mt-1.5">{{ $settings->get('max_ebook_access_per_day')?->description ?? 'How many ebooks a member can access per day.' }}</p>
                </div>

                {{-- Allow Self-Registration --}}
                <div class="flex items-center justify-between py-3 border-t border-gray-50">
                    <div>
                        <p class="text-sm font-medium text-gray-700">Allow Member Self-Registration</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $settings->get('allow_registration')?->description ?? 'Allow new members to register themselves.' }}</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="allow_registration" value="1" class="sr-only peer"
                               {{ ($settings->get('allow_registration')?->value ?? '1') == '1' ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                {{-- Maintenance Mode --}}
                <div class="flex items-center justify-between py-3 border-t border-gray-50">
                    <div>
                        <p class="text-sm font-medium text-gray-700">Maintenance Mode</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $settings->get('maintenance_mode')?->description ?? 'When enabled, only admins can access the site.' }}</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="maintenance_mode" value="1" class="sr-only peer"
                               {{ ($settings->get('maintenance_mode')?->value ?? '0') == '1' ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-500"></div>
                    </label>
                </div>
            </div>
        </div>

        {{-- Card 3: Subscription --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100">
                <div class="w-9 h-9 bg-purple-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 text-sm">Subscription</h3>
                    <p class="text-xs text-gray-400">Default plan assigned to new members</p>
                </div>
            </div>
            <div class="p-6">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Default Subscription Plan for New Members</label>
                <div class="relative">
                    <select name="default_subscription_plan"
                            class="w-full appearance-none border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
                        <option value="">— None (no automatic plan) —</option>
                        @foreach($plans as $plan)
                            <option value="{{ $plan->id }}"
                                    {{ ($settings->get('default_subscription_plan')?->value == $plan->id) ? 'selected' : '' }}>
                                {{ $plan->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-1.5">{{ $settings->get('default_subscription_plan')?->description ?? 'Automatically assign this plan when a new member registers.' }}</p>
            </div>
        </div>

    </div>{{-- /max-w-3xl --}}
</form>

@push('scripts')
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
