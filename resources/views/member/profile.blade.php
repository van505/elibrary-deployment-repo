@extends('layouts.member')
@section('title', 'My Profile')

@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">My Profile</h2>
        <p class="text-sm text-gray-500 mt-1">Update your personal information and password</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('member.profile.update') }}" enctype="multipart/form-data">
            @csrf @method('PUT')

            {{-- Header: Avatar Upload & Streak Info --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-6 pb-6 border-b border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="w-20 h-20 rounded-full overflow-hidden bg-blue-600 flex items-center justify-center flex-shrink-0 ring-2 ring-blue-100">
                        @if($member->avatar)
                            <img src="{{ Storage::url($member->avatar) }}"
                                 alt="Profile Photo"
                                 class="w-full h-full object-cover">
                        @else
                            <span class="text-white text-2xl font-bold">
                                {{ strtoupper(substr($member->first_name ?? auth()->user()->email, 0, 1)) }}
                            </span>
                        @endif
                    </div>
                    <div>
                        <label class="cursor-pointer text-blue-600 hover:text-blue-700 text-sm font-medium hover:underline">
                            Change Photo
                            <input type="file" name="avatar" accept="image/*" class="hidden"
                                   onchange="this.closest('form').submit()">
                        </label>
                        <p class="text-gray-400 text-xs mt-1">JPG, PNG or GIF. Max 2MB.</p>
                        @error('avatar') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Reading Streak Summary --}}
                <div class="bg-gradient-to-br from-orange-50 to-orange-100 border border-orange-200 rounded-xl p-4 flex items-center gap-4 shadow-sm min-w-[200px]">
                    <div class="text-3xl filter drop-shadow-sm">🔥</div>
                    <div>
                        <p class="font-bold text-orange-600 leading-tight">{{ $member->current_streak ?? 0 }}-Day Streak</p>
                        <p class="text-[10px] text-gray-500 mt-1 uppercase tracking-wider font-semibold">Longest: {{ $member->longest_streak ?? 0 }} Days</p>
                        <p class="text-[10px] text-gray-400 mt-0.5">Last read: {{ $member->last_read_date ? \Carbon\Carbon::parse($member->last_read_date)->format('M d, Y') : 'Never' }}</p>
                    </div>
                </div>
            </div>

            {{-- Personal Info --}}
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4 border-b border-gray-100 pb-2">Personal Information</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">First Name <span class="text-red-500">*</span></label>
                        <input type="text" name="first_name" value="{{ old('first_name', $member->first_name) }}" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        @error('first_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Last Name <span class="text-red-500">*</span></label>
                        <input type="text" name="last_name" value="{{ old('last_name', $member->last_name) }}" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        @error('last_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Middle Name <span class="text-gray-400 text-xs">(optional)</span></label>
                    <input type="text" name="middle_name" value="{{ old('middle_name', $member->middle_name) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    @error('middle_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone <span class="text-gray-400 text-xs">(optional)</span></label>
                    <input type="text" name="phone" value="{{ old('phone', $member->phone) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                           placeholder="+63 912 345 6789">
                    @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Address <span class="text-gray-400 text-xs">(optional)</span></label>
                    <textarea name="address" rows="2"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none resize-none">{{ old('address', $member->address) }}</textarea>
                    @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" value="{{ auth()->user()->email }}" disabled
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-50 text-gray-400 cursor-not-allowed">
                    <p class="text-xs text-gray-400 mt-1">Email cannot be changed here. Contact admin if needed.</p>
                </div>
            </div>

            {{-- Password Change --}}
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4 border-b border-gray-100 pb-2">Change Password</h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Current Password <span class="text-gray-400 text-xs">(required to change password)</span></label>
                        <input type="password" name="current_password"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                               placeholder="Enter only if changing password">
                        @error('current_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">New Password <span class="text-gray-400 text-xs">(optional, min 8 chars)</span></label>
                        <input type="password" name="new_password"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                               placeholder="Leave blank to keep current">
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
                <a href="{{ route('member.dashboard') }}" class="text-gray-500 hover:text-gray-700 text-sm">Cancel</a>
            </div>
        </form>
    </div>

    {{-- Two Factor Auth --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mt-6">
        <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-1 border-b border-gray-100 pb-2">Two-Factor Authentication (Email OTP)</h3>
        <p class="text-xs text-gray-400 mb-4">When enabled, a 6-digit verification code will be sent to your registered email on every login.</p>

        @if(auth()->user()->two_factor_enabled)
            <p class="text-sm text-green-600 mb-4 font-medium flex items-center gap-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                2FA is <strong>enabled</strong> — codes sent to {{ auth()->user()->email }}
            </p>
            <form action="{{ route('2fa.disable') }}" method="POST">
                @csrf @method('DELETE')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm password to disable <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required
                           class="w-full max-w-sm border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    Disable 2FA
                </button>
            </form>
        @else
            <p class="text-sm text-gray-500 mb-4">2FA is currently <strong class="text-gray-700">disabled</strong>. Enable it to receive login codes on your email.</p>
            <form action="{{ route('2fa.enable') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm password to enable <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required
                           class="w-full max-w-sm border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    Enable 2FA
                </button>
            </form>
        @endif
    </div>
</div>
@endsection
