<x-guest-layout>
    <!-- Session Status -->
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
        <div class="mb-4 font-medium text-sm text-yellow-600 bg-yellow-50 p-3 rounded-lg border border-yellow-200 flex items-center gap-2">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            Warning: {{ $retriesLeft }} attempt{{ $retriesLeft === 1 ? '' : 's' }} remaining before lockout.
        </div>
    @endif

    @if($lockoutSeconds > 0)
        <div class="mb-4 font-medium text-sm text-red-600 bg-red-50 p-3 rounded-lg border border-red-200 flex items-center gap-2" id="lockout-container">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>
                Account locked. Please try again in <strong id="lockout-timer">{{ gmdate("i:s", $lockoutSeconds) }}</strong> minutes.
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let seconds = {{ $lockoutSeconds }};
                const timerEl = document.getElementById('lockout-timer');
                const btn = document.querySelector('button[type="submit"]');
                
                // Disable login button
                if (btn) btn.disabled = true;

                const interval = setInterval(() => {
                    seconds--;
                    if (seconds <= 0) {
                        clearInterval(interval);
                        timerEl.innerHTML = "00:00";
                        if (btn) btn.disabled = false;
                        document.getElementById('lockout-container').classList.add('hidden');
                        location.reload(); // Reload to clear rate limit state cleanly
                    } else {
                        let m = Math.floor(seconds / 60);
                        let s = seconds % 60;
                        timerEl.innerHTML = (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
                    }
                }, 1000);
            });
        </script>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
