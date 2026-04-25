@extends('layouts.admin')
@section('title', 'My Profile')

@section('content')
<div class="max-w-5xl">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">My Profile</h1>
        <p class="text-gray-500 text-sm mt-1">Manage your account information, password, and security settings</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- LEFT: Identity Card --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center sticky top-6">
                <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-blue-700 rounded-full flex items-center justify-center text-white text-3xl font-bold mx-auto mb-4 shadow-lg">
                    {{ strtoupper(substr(auth()->user()->first_name ?? auth()->user()->email ?? 'A', 0, 1)) }}
                </div>
                <h2 class="text-lg font-bold text-gray-900">{{ auth()->user()->display_name }}</h2>
                <p class="text-gray-400 text-sm mb-3">{{ auth()->user()->email }}</p>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-blue-700 text-xs font-semibold rounded-full">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"/></svg>
                    Administrator
                </span>
                <div class="mt-6 pt-5 border-t border-gray-100 text-left space-y-3">
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <svg class="w-4 h-4 text-gray-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span class="truncate">{{ auth()->user()->email }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <svg class="w-4 h-4 text-gray-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        2FA: <span class="{{ auth()->user()->two_factor_enabled ? 'text-green-600 font-semibold' : 'text-gray-400' }}">
                            {{ auth()->user()->two_factor_enabled ? 'Enabled' : 'Disabled' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT: Form Cards --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Card 1: Personal Info --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100">
                    <div class="w-9 h-9 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 text-sm">Personal Information</h3>
                        <p class="text-xs text-gray-400">Update your name and email address</p>
                    </div>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.profile.update') }}">
                        @csrf @method('PUT')
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">First Name</label>
                                <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" placeholder="First name"
                                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                @error('first_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Last Name</label>
                                <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" placeholder="Last name"
                                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                @error('last_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Email Address <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            <p class="text-xs text-amber-600 mt-1.5">⚠ If you have 2FA enabled, changing your email updates where login codes are sent.</p>
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="flex items-center gap-3">
                            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Save Changes
                            </button>
                            <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-400 hover:text-gray-600">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Card 2: Change Password --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100">
                    <div class="w-9 h-9 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 text-sm">Change Password</h3>
                        <p class="text-xs text-gray-400">Current password required to make any changes</p>
                    </div>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.profile.update') }}">
                        @csrf @method('PUT')
                        <div class="space-y-4 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Current Password <span class="text-red-500">*</span></label>
                                <input type="password" name="current_password" required placeholder="Enter your current password"
                                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                @error('current_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">New Password <span class="text-gray-400 text-xs">(min 8 chars)</span></label>
                                <input type="password" name="new_password" id="newPasswordInput"
                                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                                       placeholder="Leave blank to keep current password"
                                       oninput="updateStrengthMeter(this.value)">
                                <div class="mt-2">
                                    <div class="flex gap-1 mb-1">
                                        <div id="sm-bar-1" class="h-1 flex-1 rounded-full bg-gray-200 transition-colors duration-300"></div>
                                        <div id="sm-bar-2" class="h-1 flex-1 rounded-full bg-gray-200 transition-colors duration-300"></div>
                                        <div id="sm-bar-3" class="h-1 flex-1 rounded-full bg-gray-200 transition-colors duration-300"></div>
                                        <div id="sm-bar-4" class="h-1 flex-1 rounded-full bg-gray-200 transition-colors duration-300"></div>
                                    </div>
                                    <p id="sm-label" class="text-xs text-gray-400"></p>
                                </div>
                                @error('new_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Confirm New Password</label>
                                <input type="password" name="new_password_confirmation" placeholder="Confirm new password"
                                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            </div>
                        </div>
                        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                            Update Password
                        </button>
                    </form>
                </div>
            </div>

            {{-- Card 3: Two-Factor Authentication --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100">
                    <div class="w-9 h-9 {{ auth()->user()->two_factor_enabled ? 'bg-green-100' : 'bg-gray-100' }} rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 {{ auth()->user()->two_factor_enabled ? 'text-green-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <h3 class="font-semibold text-gray-900 text-sm">Two-Factor Authentication</h3>
                            @if(auth()->user()->two_factor_enabled)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-green-100 text-green-700 text-xs font-semibold rounded-full"><span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> Enabled</span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-gray-100 text-gray-500 text-xs font-semibold rounded-full"><span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span> Disabled</span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-400">Email OTP sent on every login</p>
                    </div>
                </div>
                <div class="p-6">
                    @if(auth()->user()->two_factor_enabled)
                        <p class="text-sm text-gray-600 mb-4">2FA is active. Codes are sent to <strong class="text-gray-900">{{ auth()->user()->email }}</strong>.</p>
                        <form action="{{ route('2fa.disable') }}" method="POST">
                            @csrf @method('DELETE')
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Confirm password to disable <span class="text-red-500">*</span></label>
                                <input type="password" name="password" required
                                       class="w-full max-w-sm border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none">
                                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-red-200 text-red-600 text-sm font-medium rounded-xl hover:bg-red-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                Disable 2FA
                            </button>
                        </form>
                    @else
                        <p class="text-sm text-gray-600 mb-4">2FA is disabled. Enable it to add an extra layer of security.</p>
                        <form action="{{ route('2fa.enable') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Confirm password to enable <span class="text-red-500">*</span></label>
                                <input type="password" name="password" required
                                       class="w-full max-w-sm border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-xl hover:bg-gray-800 transition-colors shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                Enable 2FA
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
function updateStrengthMeter(val) {
    const bars  = [1,2,3,4].map(i => document.getElementById('sm-bar-' + i));
    const label = document.getElementById('sm-label');
    const len   = val.length;
    let level, color, text;
    if (len === 0)     { level=0; color='bg-gray-200';  text=''; }
    else if (len < 6)  { level=1; color='bg-red-400';   text='Weak'; }
    else if (len < 10) { level=2; color='bg-amber-400'; text='Fair'; }
    else if (len < 14) { level=3; color='bg-blue-400';  text='Good'; }
    else               { level=4; color='bg-green-500'; text='Strong'; }
    bars.forEach((bar, i) => {
        bar.className = 'h-1 flex-1 rounded-full transition-colors duration-300 ' + (i < level ? color : 'bg-gray-200');
    });
    label.textContent = text;
    label.className   = 'text-xs mt-0.5 ' + (level===0?'text-gray-400':level===1?'text-red-500':level===2?'text-amber-500':level===3?'text-blue-500':'text-green-600');
}
</script>
@endpush
@endsection
