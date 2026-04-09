@section('title', 'Create Account')

<x-guest-layout>

    {{-- ===== LEFT PANEL ===== --}}
    <x-slot name="leftPanel">
        <h1 class="text-4xl lg:text-5xl font-extrabold text-white leading-tight mb-4 mt-[10%]">
            Join ELibrary
        </h1>
        <p class="text-blue-100 text-lg leading-relaxed mb-12">
            Create your account and start your<br>unlimited reading journey today.
        </p>

        <ul class="space-y-6">
            <li class="flex text-white font-medium text-base items-center">
                <svg class="w-5 h-5 mr-4 text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
                Free to join as a reader
            </li>
            <li class="flex text-white font-medium text-base items-center">
                <svg class="w-5 h-5 mr-4 text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
                Browse thousands of ebooks
            </li>
            <li class="flex text-white font-medium text-base items-center">
                <svg class="w-5 h-5 mr-4 text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
                Safe and private community
            </li>
        </ul>
    </x-slot>

    <x-slot name="bottomLink">
        Already have an account? <a href="{{ route('login') }}" class="text-white underline underline-offset-2 font-bold hover:text-blue-50 transition-colors">Sign in here</a>
    </x-slot>

    {{-- ===== RIGHT PANEL (FORM) ===== --}}

    <h2 class="text-2xl font-bold text-gray-900 mb-8 text-center lg:text-left">Create Account</h2>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email *</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   required autocomplete="username"
                   class="auth-input" placeholder="">
            @error('email')
                <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Password *</label>
            <div class="relative">
                <input id="password" type="password" name="password"
                       required autocomplete="new-password"
                       class="auth-input pr-10" placeholder=""
                       oninput="checkPasswordStrength(this.value)">
                <button type="button" onclick="togglePassword('password')"
                        class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-blue-600 transition-colors focus:outline-none">
                    <svg id="password-eye" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg id="password-eye-slash" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>
            @error('password')
                <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
            @enderror

            {{-- Password Strength --}}
            <div class="mt-2.5 hidden" id="password-strength">
                <div class="flex items-center gap-2 mb-2">
                    <div class="flex-1 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                        <div id="strength-bar" class="h-full transition-all duration-300" style="width:0%"></div>
                    </div>
                </div>
                <div class="flex gap-4 text-xs text-gray-500">
                    <span id="req-length" class="flex items-center gap-1">
                        <svg id="icon-length" class="w-3 h-3 text-gray-300" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="5"/></svg>
                        8+ chars
                    </span>
                    <span id="req-number" class="flex items-center gap-1">
                        <svg id="icon-number" class="w-3 h-3 text-gray-300" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="5"/></svg>
                        Number
                    </span>
                    <span id="req-special" class="flex items-center gap-1">
                        <svg id="icon-special" class="w-3 h-3 text-gray-300" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="5"/></svg>
                        Special
                    </span>
                </div>
            </div>
        </div>

        {{-- Confirm Password --}}
        <div>
            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1.5">Confirm Password *</label>
            <div class="relative">
                <input id="password_confirmation" type="password" name="password_confirmation"
                       required autocomplete="new-password"
                       class="auth-input pr-10" placeholder="">
                <button type="button" onclick="togglePassword('password_confirmation')"
                        class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-blue-600 transition-colors focus:outline-none">
                    <svg id="password_confirmation-eye" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg id="password_confirmation-eye-slash" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>
            @error('password_confirmation')
                <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Terms --}}
        <div class="flex items-start gap-2.5 pt-3 pb-2">
            <input type="checkbox" id="terms" name="terms" required
                   class="mt-0.5 w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
            <label for="terms" class="text-sm text-gray-600 cursor-pointer">
                I agree to the
                <a href="#" class="text-blue-600 hover:text-blue-800 font-semibold">Terms of Service</a>
                and
                <a href="#" class="text-blue-600 hover:text-blue-800 font-semibold">Privacy Policy</a>
            </label>
        </div>

        {{-- Submit --}}
        <button type="submit" class="auth-btn">Create Free Account</button>
    </form>

    {{-- Divider --}}
    <div class="relative my-7">
        <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div></div>
        <div class="relative flex justify-center text-xs">
            <span class="px-3 bg-white text-gray-400">or sign up with</span>
        </div>
    </div>

    {{-- Social --}}
    <div class="grid grid-cols-2 gap-3 mb-4">
        <button type="button" class="flex items-center justify-center gap-2 px-4 py-2 border border-gray-200 rounded-xl hover:bg-gray-50 transition-all text-sm font-semibold text-gray-700">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                <path d="M1 1h22v22H1z" fill="none"/>
            </svg>
            Google
        </button>
        <button type="button" class="flex items-center justify-center gap-2 px-4 py-2 border border-gray-200 rounded-xl hover:bg-gray-50 transition-all text-sm font-semibold text-gray-700">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.05 20.28c-.98.95-2.05.8-3.08.35-1.09-.46-2.09-.48-3.24 0-1.44.62-2.2.44-3.06-.35C2.79 15.25 3.51 7.59 9.05 7.31c1.35.07 2.29.74 3.08.8 1.18-.09 2.31-.93 3.57-.84 1.51.05 2.95.72 3.94 1.81-3.48 2.05-2.85 6.08.41 7.42-1.02 1.68-1.92 2.76-3 3.78zM12.03 7.25c-.15-2.23 1.66-4.07 3.74-4.25.32 2.4-1.89 4.34-3.74 4.25z"/>
            </svg>
            Apple
        </button>
    </div>

    {{-- Mobile login link --}}
    <p class="lg:hidden text-center text-sm text-gray-500 mt-8">
        Already have an account?
        <a href="{{ route('login') }}" class="font-bold text-blue-600 hover:text-blue-800 transition-colors">Sign in here</a>
    </p>

    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            const eye = document.getElementById(id + '-eye');
            const slash = document.getElementById(id + '-eye-slash');
            if (input.type === 'password') {
                input.type = 'text'; eye.classList.add('hidden'); slash.classList.remove('hidden');
            } else {
                input.type = 'password'; slash.classList.add('hidden'); eye.classList.remove('hidden');
            }
        }

        function checkPasswordStrength(password) {
            const strengthDiv = document.getElementById('password-strength');
            const strengthBar = document.getElementById('strength-bar');

            if (password.length > 0) {
                strengthDiv.classList.remove('hidden');
            } else {
                strengthDiv.classList.add('hidden');
                return;
            }

            let strength = 0;
            const hasLength = password.length >= 8;
            const hasNumber = /\d/.test(password);
            const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);

            if (hasLength) strength++;
            if (hasNumber) strength++;
            if (hasSpecial) strength++;

            updateRequirement('req-length', 'icon-length', hasLength);
            updateRequirement('req-number', 'icon-number', hasNumber);
            updateRequirement('req-special', 'icon-special', hasSpecial);

            const colors = ['bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-green-500'];
            const widths = ['25%', '50%', '75%', '100%'];

            strengthBar.className = 'h-full transition-all duration-300 ' + colors[strength];
            strengthBar.style.width = widths[strength];
        }

        function updateRequirement(elId, iconId, met) {
            const el = document.getElementById(elId);
            const icon = document.getElementById(iconId);
            const checkPath = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>';
            const dotPath = '<circle cx="12" cy="12" r="5"/>';

            if (met) {
                icon.setAttribute('stroke', 'currentColor');
                icon.setAttribute('fill', 'none');
                icon.innerHTML = checkPath;
                icon.className = 'w-3 h-3 text-green-500';
                el.className = el.className.replace('text-gray-500', 'text-green-600');
            } else {
                icon.setAttribute('fill', 'currentColor');
                icon.removeAttribute('stroke');
                icon.innerHTML = dotPath;
                icon.className = 'w-3 h-3 text-gray-300';
                el.className = el.className.replace('text-green-600', 'text-gray-500');
            }
        }
    </script>

</x-guest-layout>
