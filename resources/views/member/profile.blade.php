@extends('layouts.member')
@section('title', 'My Profile')

@push('breadcrumbs')
<nav class="flex items-center text-sm">
    <a href="{{ route('member.dashboard') }}" class="text-gray-400 hover:text-gray-600 transition-colors duration-150">Home</a>
    <span class="text-gray-300 mx-1.5 select-none">›</span>
    <span class="text-gray-700 font-medium">Profile &amp; Settings</span>
</nav>
@endpush

@section('content')
<style>[x-cloak] { display: none !important; }</style>
<div class="max-w-3xl" x-data="{
    activeTab: window.location.hash === '#security' ? 'security' : 'profile',
    init() {
        window.addEventListener('hashchange', () => {
            this.activeTab = window.location.hash === '#security' ? 'security' : 'profile'
        })
    }
}">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">My Profile</h2>
        <p class="text-sm text-gray-500 mt-1">Manage your account settings and preferences</p>
    </div>

    {{-- The Tabs --}}
    <div class="flex border-b border-gray-200 mb-6">
        <button type="button"
                @click="activeTab = 'profile'; window.location.hash = ''"
                :class="activeTab === 'profile' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="px-6 py-3 border-b-2 font-medium text-sm transition-colors duration-150">
            Personal Info
        </button>
        <button type="button"
                @click="activeTab = 'security'; window.location.hash = 'security'"
                :class="activeTab === 'security' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="px-6 py-3 border-b-2 font-medium text-sm transition-colors duration-150">
            Security &amp; Passwords
        </button>
    </div>

    {{-- ═══════════════════════════════════════ --}}
    {{-- PROFILE TAB (inside the main PUT form) --}}
    {{-- ═══════════════════════════════════════ --}}
    <form method="POST" action="{{ route('member.profile.update') }}" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div x-show="activeTab === 'profile'"
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">

            {{-- ── Avatar + Streak ── --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8 pb-6 border-b border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="w-20 h-20 rounded-full overflow-hidden bg-indigo-600 flex items-center justify-center flex-shrink-0 ring-4 ring-indigo-50">
                        @if($member->avatar)
                            <img src="{{ Storage::url($member->avatar) }}" alt="Profile Photo" class="w-full h-full object-cover">
                        @else
                            <span class="text-white text-2xl font-bold">
                                {{ strtoupper(substr($member->first_name ?? auth()->user()->email, 0, 1)) }}
                            </span>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">{{ $member->full_name ?? auth()->user()->email }}</p>
                        <label class="cursor-pointer text-indigo-600 hover:text-indigo-700 text-xs font-medium hover:underline mt-1 block">
                            Change Photo
                            <input type="file" name="avatar" accept="image/*" class="hidden" onchange="this.closest('form').submit()">
                        </label>
                        <p class="text-xs text-gray-400 mt-0.5">JPG, PNG or GIF. Max 2MB.</p>
                        @error('avatar') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Reading Streak Badge --}}
                <div class="bg-gradient-to-br from-orange-50 to-orange-100 border border-orange-200 rounded-xl p-4 flex items-center gap-4 shadow-sm min-w-[200px]">
                    <div class="text-3xl">🔥</div>
                    <div>
                        <p class="font-bold text-orange-600 leading-tight">{{ $member->current_streak ?? 0 }}-Day Streak</p>
                        <p class="text-[10px] text-gray-500 mt-1 uppercase tracking-wider font-semibold">Longest: {{ $member->longest_streak ?? 0 }} Days</p>
                        <p class="text-[10px] text-gray-400 mt-0.5">Last read: {{ $member->last_read_date ? \Carbon\Carbon::parse($member->last_read_date)->format('M d, Y') : 'Never' }}</p>
                    </div>
                </div>
            </div>

            {{-- ── Personal Information Fields ── --}}
            <h3 class="text-xs font-bold text-gray-500 tracking-wider uppercase mb-6 pb-2 border-b border-gray-100">Personal Information</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">First Name <span class="text-red-500">*</span></label>
                    <input type="text" name="first_name" value="{{ old('first_name', $member->first_name) }}" required
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors duration-150">
                    @error('first_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Last Name <span class="text-red-500">*</span></label>
                    <input type="text" name="last_name" value="{{ old('last_name', $member->last_name) }}" required
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors duration-150">
                    @error('last_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Middle Name <span class="text-gray-400 text-xs">(optional)</span></label>
                <input type="text" name="middle_name" value="{{ old('middle_name', $member->middle_name) }}"
                       class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors duration-150">
                @error('middle_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone <span class="text-gray-400 text-xs">(optional)</span></label>
                <input type="text" name="phone" value="{{ old('phone', $member->phone) }}"
                       class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors duration-150"
                       placeholder="+63 912 345 6789">
                @error('phone') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Address <span class="text-gray-400 text-xs">(optional)</span></label>
                <textarea name="address" rows="2"
                          class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors duration-150 resize-none">{{ old('address', $member->address) }}</textarea>
                @error('address') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Email — read-only --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <input type="email" value="{{ auth()->user()->email }}"
                       readonly tabindex="-1"
                       class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-500 bg-slate-50 cursor-not-allowed focus:outline-none">
                <p class="text-xs text-slate-400 mt-1">Email address cannot be changed.</p>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button"
                        @click="$el.closest('form').reset()"
                        class="text-gray-600 hover:bg-gray-100 hover:text-gray-900 font-medium px-4 py-2 rounded-lg transition-colors duration-150 text-sm">
                    Cancel
                </button>
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-6 py-2 rounded-lg transition-colors duration-150 text-sm shadow-sm">
                    Save Changes
                </button>
            </div>
        </div>

        {{-- ════════════════════════════════════════════ --}}
        {{-- SECURITY TAB — Change Password (same form) --}}
        {{-- ════════════════════════════════════════════ --}}
        <div x-show="activeTab === 'security'"
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">

            <h3 class="text-xs font-bold text-gray-500 tracking-wider uppercase mb-6 pb-2 border-b border-gray-100">Change Password</h3>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Current Password <span class="text-gray-400 text-xs">(required to change password)</span></label>
                    <input type="password" name="current_password"
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors duration-150"
                           placeholder="Enter only if changing password">
                    @error('current_password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">New Password <span class="text-gray-400 text-xs">(optional, min 8 chars)</span></label>
                    <input type="password" name="new_password"
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors duration-150"
                           placeholder="Leave blank to keep current">
                    @error('new_password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                    <input type="password" name="new_password_confirmation"
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors duration-150"
                           placeholder="Confirm new password">
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-6 py-2 rounded-lg transition-colors duration-150 text-sm shadow-sm">
                    Update Password
                </button>
            </div>
        </div>
    </form>

    {{-- ══════════════════════════════════════ --}}
    {{-- SECURITY TAB — 2FA (separate form)    --}}
    {{-- ══════════════════════════════════════ --}}
    <div x-show="activeTab === 'security'"
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">

        <div class="flex items-center justify-between pb-2 border-b border-gray-100 mb-4">
            <h3 class="text-xs font-bold text-gray-500 tracking-wider uppercase">Two-Factor Authentication</h3>
            @if(auth()->user()->two_factor_enabled)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold tracking-wider uppercase bg-emerald-100 text-emerald-700 border border-emerald-200">
                    ● Enabled
                </span>
            @else
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold tracking-wider uppercase bg-slate-100 text-slate-700 border border-slate-200">
                    ● Disabled
                </span>
            @endif
        </div>

        <p class="text-xs text-gray-400 mb-5">When enabled, a 6-digit verification code will be sent to your registered email on every login.</p>

        @if(auth()->user()->two_factor_enabled)
            <p class="text-sm text-emerald-600 mb-4 font-medium flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Codes are sent to <strong class="ml-1">{{ auth()->user()->email }}</strong>
            </p>
            <form action="{{ route('2fa.disable') }}" method="POST">
                @csrf @method('DELETE')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm your password to disable <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors duration-150">
                    @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex justify-end mt-4">
                    <button type="submit"
                            class="bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 font-medium px-6 py-2 rounded-lg transition-colors duration-150 text-sm">
                        Disable 2FA
                    </button>
                </div>
            </form>
        @else
            <p class="text-sm text-gray-500 mb-4">Enable two-factor authentication to add an extra layer of security to your account.</p>
            <form action="{{ route('2fa.enable') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm your password to enable <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors duration-150">
                    @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex justify-end mt-4">
                    <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-6 py-2 rounded-lg transition-colors duration-150 text-sm shadow-sm">
                        Enable 2FA
                    </button>
                </div>
            </form>
        @endif
    </div>

</div>
@endsection
