@extends('layouts.admin')
@section('title', 'System Settings')

@section('content')
<div class="max-w-2xl space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">System Settings</h2>
        <p class="text-sm text-gray-500 mt-1">Manage global application settings</p>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        {{-- Site Identity --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-4">
            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4 border-b border-gray-100 pb-2">Site Identity</h3>

            {{-- Site Name --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Site Name</label>
                <input type="text" name="site_name"
                       value="{{ old('site_name', $settings->get('site_name')?->value ?? 'ELibrary') }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                <p class="text-xs text-gray-400 mt-1">{{ $settings->get('site_name')?->description }}</p>
            </div>

            {{-- Site Logo --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Site Logo</label>
                @if($settings->get('site_logo')?->value)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $settings->get('site_logo')->value) }}" alt="Current Logo" class="h-12 rounded">
                        <p class="text-xs text-gray-400 mt-1">Current logo</p>
                    </div>
                @endif
                <input type="file" name="site_logo" accept="image/*"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                <p class="text-xs text-gray-400 mt-1">{{ $settings->get('site_logo')?->description }}</p>
            </div>
        </div>

        {{-- Access Control --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-4">
            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4 border-b border-gray-100 pb-2">Access Control</h3>

            {{-- Max Ebook Access Per Day --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Max Ebook Access Per Day (per member)</label>
                <input type="number" name="max_ebook_access_per_day" min="1"
                       value="{{ old('max_ebook_access_per_day', $settings->get('max_ebook_access_per_day')?->value ?? 10) }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                <p class="text-xs text-gray-400 mt-1">{{ $settings->get('max_ebook_access_per_day')?->description }}</p>
            </div>

            {{-- Allow Self Registration --}}
            <div class="flex items-center justify-between py-3 border-t border-gray-100">
                <div>
                    <p class="text-sm font-medium text-gray-700">Allow Member Self-Registration</p>
                    <p class="text-xs text-gray-400">{{ $settings->get('allow_registration')?->description }}</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="allow_registration" value="1" class="sr-only peer"
                           {{ ($settings->get('allow_registration')?->value ?? '1') == '1' ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                </label>
            </div>

            {{-- Maintenance Mode --}}
            <div class="flex items-center justify-between py-3 border-t border-gray-100">
                <div>
                    <p class="text-sm font-medium text-gray-700">Maintenance Mode</p>
                    <p class="text-xs text-gray-400">{{ $settings->get('maintenance_mode')?->description }}</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="maintenance_mode" value="1" class="sr-only peer"
                           {{ ($settings->get('maintenance_mode')?->value ?? '0') == '1' ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-500"></div>
                </label>
            </div>
        </div>

        {{-- Subscription Settings --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-4">
            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4 border-b border-gray-100 pb-2">Subscription</h3>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Default Subscription Plan for New Members</label>
                <select name="default_subscription_plan"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">— None (no automatic plan) —</option>
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}"
                                {{ ($settings->get('default_subscription_plan')?->value == $plan->id) ? 'selected' : '' }}>
                            {{ $plan->name }}
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-400 mt-1">{{ $settings->get('default_subscription_plan')?->description }}</p>
            </div>
        </div>

        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg text-sm font-medium transition-colors">
            Save Settings
        </button>
    </form>
</div>
@endsection
