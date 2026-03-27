@extends('layouts.admin')
@section('title', 'My Profile')

@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">My Profile</h2>
        <p class="text-sm text-gray-500 mt-1">Manage your admin account settings</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.profile.update') }}">
            @csrf @method('PUT')

            {{-- Name Fields --}}
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4 border-b border-gray-100 pb-2">Account Information</h3>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">First Name <span class="text-gray-400 text-xs">(optional)</span></label>
                        <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                               placeholder="First name">
                        @error('first_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Last Name <span class="text-gray-400 text-xs">(optional)</span></label>
                        <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                               placeholder="Last name">
                        @error('last_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Password Change --}}
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4 border-b border-gray-100 pb-2">Change Password</h3>
                <p class="text-xs text-gray-400 mb-4">Current password is required to save any changes.</p>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Current Password <span class="text-red-500">*</span></label>
                        <input type="password" name="current_password" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                               placeholder="Enter your current password">
                        @error('current_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">New Password <span class="text-gray-400 text-xs">(optional, min 8 chars)</span></label>
                        <input type="password" name="new_password"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                               placeholder="Leave blank to keep current password">
                        @error('new_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                        <input type="password" name="new_password_confirmation"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                               placeholder="Confirm new password">
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg text-sm font-medium transition-colors">
                    Save Changes
                </button>
                <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gray-700 text-sm">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
