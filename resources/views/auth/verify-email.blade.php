@section('title', 'Verify Email')

<x-guest-layout>

    {{-- ===== LEFT PANEL ===== --}}
    <x-slot name="leftPanel">
        <h1 class="text-4xl lg:text-5xl font-extrabold text-white leading-tight mb-4 mt-[10%]">
            Check Your<br>Inbox
        </h1>
        <p class="text-blue-100 text-lg leading-relaxed mb-12">
            One quick step before you start reading.<br>We've sent a confirmation link to your email.
        </p>

        <ul class="space-y-6">
            <li class="flex text-white font-medium text-base items-center">
                <svg class="w-5 h-5 mr-4 text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
                Open the email we just sent you
            </li>
            <li class="flex text-white font-medium text-base items-center">
                <svg class="w-5 h-5 mr-4 text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
                Click "Verify Email Address"
            </li>
            <li class="flex text-white font-medium text-base items-center">
                <svg class="w-5 h-5 mr-4 text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
                Start exploring your ebook library!
            </li>
        </ul>
    </x-slot>

    <x-slot name="bottomLink">
        Already verified? <a href="{{ route('login') }}" class="text-white underline underline-offset-2 font-bold hover:text-blue-50 transition-colors">Sign in</a>
    </x-slot>

    {{-- ===== RIGHT PANEL ===== --}}

    <div class="flex flex-col items-center text-center mb-8">
        <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mb-5 ring-8 ring-blue-50/50">
            <svg class="w-10 h-10 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Verify Your Email</h2>
        <p class="text-gray-500 text-sm max-w-xs">
            We sent a verification link to <strong class="text-gray-700">{{ auth()->user()->email }}</strong>.
            Click the link in the email to activate your account.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-5 p-3.5 rounded-lg bg-green-50 border border-green-200 flex items-center gap-3 text-sm text-green-700 font-medium">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            A new verification link has been sent to your email address.
        </div>
    @endif

    @if (session('status'))
        <div class="mb-5 p-3.5 rounded-lg bg-blue-50 border border-blue-200 flex items-center gap-3 text-sm text-blue-700 font-medium">
            <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('status') }}
        </div>
    @endif

    <div class="space-y-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit"
                    class="w-full flex justify-center items-center gap-2 py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-colors duration-200 shadow-sm shadow-blue-200">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Resend Verification Email
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="w-full flex justify-center items-center gap-2 py-3 px-4 bg-gray-50 hover:bg-gray-100 text-gray-600 font-semibold rounded-xl border border-gray-200 transition-colors duration-200">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Log Out
            </button>
        </form>
    </div>

    <p class="mt-6 text-center text-xs text-gray-400">
        Didn't receive the email? Check your spam folder or click "Resend" above.<br>
        The link expires in <strong>60 minutes</strong>.
    </p>

</x-guest-layout>
