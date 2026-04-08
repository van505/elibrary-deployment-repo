<x-guest-layout>
    <div class="mb-6 text-center">
        <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        <h1 class="text-xl font-bold text-gray-800">Check Your Email</h1>
        <p class="text-sm text-gray-500 mt-1">
            We sent a 6-digit verification code to<br>
            @php
                [$local, $domain] = explode('@', $email);
                $masked = substr($local, 0, 2) . str_repeat('*', max(strlen($local) - 2, 0));
            @endphp
            <strong class="text-gray-700">{{ $masked . '@' . $domain }}</strong>
        </p>
    </div>

    @if (session('status'))
        <div class="mb-4 text-sm font-medium text-green-600">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('2fa.verify') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="code" value="Verification Code" />
            <input type="text"
                   id="code"
                   name="code"
                   inputmode="numeric"
                   maxlength="6"
                   required
                   autofocus
                   autocomplete="one-time-code"
                   placeholder="000000"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-center text-2xl font-mono tracking-widest py-3">
            <x-input-error :messages="$errors->get('code')" class="mt-2" />
        </div>

        <x-primary-button class="w-full justify-center">
            Verify Code
        </x-primary-button>
    </form>

    <div class="mt-5 flex flex-col items-center gap-3">
        {{-- Resend --}}
        <form method="POST" action="{{ route('2fa.resend') }}">
            @csrf
            <button type="submit" class="text-sm text-indigo-600 hover:underline">
                Didn't receive a code? Resend
            </button>
        </form>

        {{-- Cancel --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-xs text-gray-400 hover:text-gray-600 hover:underline">
                Cancel and sign out
            </button>
        </form>
    </div>
</x-guest-layout>
