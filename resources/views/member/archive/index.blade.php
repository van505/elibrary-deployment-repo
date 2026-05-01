@extends('layouts.member')
@section('title', 'My Archive')

@push('breadcrumbs')
<nav class="flex items-center text-sm">
    <a href="{{ route('member.dashboard') }}" class="text-gray-400 hover:text-gray-600 transition-colors duration-150">Home</a>
    <span class="text-gray-300 mx-1.5 select-none">›</span>
    <span class="text-gray-400 mx-1.5">My Account</span>
    <span class="text-gray-300 mx-1.5 select-none">›</span>
    <span class="text-gray-700 font-medium">Archive</span>
</nav>
@endpush

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">My Archive</h2>
        <p class="text-sm text-gray-500 mt-1">View your archived items and past activities</p>
    </div>

    {{-- Empty State Placeholder --}}
    <div class="bg-slate-50 border-2 border-dashed border-slate-200 rounded-xl p-16 text-center text-gray-400 flex flex-col items-center justify-center mt-6">
        <svg class="w-16 h-16 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
        </svg>
        <p class="font-medium text-lg text-gray-700">Archive is Empty</p>
        <p class="text-sm mt-1 mb-4">You have no archived items yet. When you archive items, they will appear here securely.</p>
        <a href="{{ route('member.dashboard') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-6 rounded-lg transition-colors">
            Return to Dashboard
        </a>
    </div>
</div>
@endsection
