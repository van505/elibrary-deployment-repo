@extends('layouts.admin')
@section('title', 'System Settings')

@section('content')
<h2 class="text-2xl font-bold text-gray-800 mb-6">System Settings</h2>

<div class="bg-white rounded-xl shadow-sm p-6 max-w-lg">
    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf @method('PUT')
        <div class="space-y-5">
            @foreach($settings as $setting)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    {{ ucwords(str_replace('_', ' ', $setting->key)) }}
                    @if($setting->description)
                    <span class="text-gray-400 font-normal text-xs ml-1">— {{ $setting->description }}</span>
                    @endif
                </label>
                <input type="{{ in_array($setting->type, ['integer','decimal']) ? 'number' : 'text' }}"
                       name="settings[{{ $setting->key }}]"
                       value="{{ old('settings.'.$setting->key, $setting->value) }}"
                       step="{{ $setting->type === 'decimal' ? '0.01' : '1' }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            @endforeach
        </div>
        <div class="mt-6">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">Save Settings</button>
        </div>
    </form>
</div>
@endsection
