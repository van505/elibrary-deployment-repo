@extends('layouts.admin') 
{{-- Using admin layout as fallback, we'll actually include this via a modal or profile section, but a dedicated page works too. --}}
@section('title', 'Set Up Two-Factor Authentication')

@section('content')
<div class="max-w-2xl mx-auto py-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Set Up Two-Factor Authentication</h2>
        <p class="text-gray-500 mb-6">Scan the QR code below using an authenticator app (like Google Authenticator, Authy, or Microsoft Authenticator).</p>
        
        <div class="flex justify-center mb-6 bg-gray-50 p-6 rounded-xl border border-gray-100">
            {!! $qrCodeSvg !!}
        </div>
        
        <p class="text-sm text-center text-gray-400 mb-8 mt-4 font-mono">
            Secret Key: {{ $secret }}
        </p>

        <form method="POST" action="{{ route('2fa.confirm') }}" class="max-w-xs mx-auto">
            @csrf
            <div class="mb-4">
                <label for="code" class="block text-sm font-medium text-gray-700 mb-1 text-center">Enter Code to Confirm</label>
                <input type="text" inputmode="numeric" id="code" name="code" required autofocus autocomplete="one-time-code"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 text-center text-lg font-mono tracking-widest focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                @error('code')
                    <p class="text-red-500 text-xs mt-1 text-center">{{ $message }}</p>
                @enderror
            </div>
            
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg text-sm transition-colors shadow-sm">
                Confirm & Enable
            </button>
        </form>
    </div>
</div>
@endsection
