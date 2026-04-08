@section('title', 'Sign In')
<x-guest-layout>
    {{-- Header --}}
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Welcome Back!</h1>
        <p class="text-gray-500 text-sm">Sign in to continue your reading journey</p>
    </div>

    {{-- Session Status --}}
    <x-auth-session-status class="mb-4" :status="session('status')" />

    @php
        $throttleKey = null;
        $retriesLeft = null;
        $lockoutSeconds = 0;
        
        if (old('email')) {
            $throttleKey = Str::transliterate(Str::lower(old('email')).'|'.request()->ip());
            if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($throttleKey, 5)) {
                $lockoutSeconds = \Illuminate\Support\Facades\RateLimiter::availableIn($throttleKey);
            } else {
                $retriesLeft = \Illuminate\Support\Facades\RateLimiter::retriesLeft($throttleKey, 5);
            }
        }
    @endphp

    @if($retriesLeft !== null && $retriesLeft <= 2 && $retriesLeft > 0)
        <div class="mb-4 font-medium text-sm text-amber-600 bg-amber-50 p-3 rounded-xl border border-amber-200 flex items-center gap-2">
            <i class="fas fa-exclamation-triangle"></i>
            <span>Warning: {{ $retriesLeft }} attempt{{ $retriesLeft === 1 ? '' : 's' }} remaining before lockout.</span>
        </div>
    @endif

    @if($lockoutSeconds > 0)
        <div class="mb-4 font-medium text-sm text-red-600 bg-red-50 p-3 rounded-xl border border-red-200 flex items-center gap-2" id="lockout-container">
            <i class="fas fa-lock"></i>
            <div>
                Account locked. Please try again in <strong id="lockout-timer">{{ gmdate("i:s", $lockoutSeconds) }}</strong>.
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let seconds = {{ $lockoutSeconds }};
                const timerEl = document.getElementById('lockout-timer');
                const btn = document.querySelector('button[type="submit"]');
                
                if (btn) btn.disabled = true;

                const interval = setInterval(() => {
                    seconds--;
                    if (seconds <= 0) {
                        clearInterval(interval);
                        timerEl.innerHTML = "00:00";
                        if (btn) btn.disabled = false;
                        document.getElementById('lockout-container').classList.add('hidden');
                        location.reload();
                    } else {
                        let m = Math.floor(seconds / 60);
                        let s = seconds % 60;
                        timerEl.innerHTML = (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
                    }
                }, 1000);
            });
        </script>
    @endif

    {{-- Login Form --}}
    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        {{-- Email Address --}}
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                Email Address
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <i class="fas fa-envelope text-blue-400 text-sm"></i>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                       class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all"
                       placeholder="you@example.com">
            </div>
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">
                Password
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <i class="fas fa-lock text-blue-400 text-sm"></i>
                </div>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                       class="w-full pl-10 pr-10 py-2.5 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all"
                       placeholder="Enter your password">
                <button type="button" onclick="togglePassword('password')" 
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-blue-600 transition-colors">
                    <i class="fas fa-eye text-sm" id="password-eye"></i>
                </button>
            </div>
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Remember Me & Forgot Password --}}
        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="remember" id="remember" 
                       class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                <span class="text-sm text-gray-600">Remember me</span>
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors">
                    Forgot password?
                </a>
            @endif
        </div>

        {{-- Submit Button --}}
        <button type="submit" 
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2 group">
            <span>Sign In</span>
            <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
        </button>
    </form>

    {{-- Divider --}}
    <div class="relative my-5">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-200"></div>
        </div>
        <div class="relative flex justify-center text-xs">
            <span class="px-2 bg-white text-gray-400">or continue with</span>
        </div>
    </div>

    {{-- Social Login --}}
    <div class="grid grid-cols-2 gap-3">
        <button type="button" class="flex items-center justify-center gap-2 px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all">
            <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" class="w-4 h-4">
            <span class="text-sm font-medium text-gray-700">Google</span>
        </button>
        <button type="button" class="flex items-center justify-center gap-2 px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all">
            <img src="https://www.svgrepo.com/show/475647/apple-color.svg" alt="Apple" class="w-4 h-4">
            <span class="text-sm font-medium text-gray-700">Apple</span>
        </button>
    </div>

    {{-- Register Link --}}
    <div class="text-center mt-6 pt-4 border-t border-gray-100">
        <p class="text-sm text-gray-500">
            Don't have an account?
            <a href="{{ route('register') }}" class="font-semibold text-blue-600 hover:text-blue-700 transition-colors">
                Create one free
            </a>
        </p>
    </div>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const eye = document.getElementById(inputId + '-eye');
            if (input.type === 'password') {
                input.type = 'text';
                eye.classList.remove('fa-eye');
                eye.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                eye.classList.remove('fa-eye-slash');
                eye.classList.add('fa-eye');
            }
        }
    </script>
</x-guest-layout>
