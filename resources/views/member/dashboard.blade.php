@extends('layouts.member')
@section('title', 'My Dashboard')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Welcome back, {{ auth()->user()->name }}! 👋</h2>
    <p class="text-gray-500 text-sm mt-1">Here's an overview of your library activity.</p>
</div>

{{-- Stats --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 flex items-center gap-4">
        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
            </svg>
        </div>
        <div>
            <p class="text-3xl font-bold text-blue-600">{{ $activeBorrowings }}</p>
            <p class="text-sm text-gray-500 mt-1">Active Borrowings</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 flex items-center gap-4">
        <div class="w-12 h-12 bg-{{ $overdueBorrowings->count() > 0 ? 'red' : 'gray' }}-100 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-{{ $overdueBorrowings->count() > 0 ? 'red' : 'gray' }}-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-3xl font-bold text-{{ $overdueBorrowings->count() > 0 ? 'red-600' : 'gray-400' }}">{{ $overdueBorrowings->count() }}</p>
            <p class="text-sm text-gray-500 mt-1">Overdue Books</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 flex items-center gap-4">
        <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <div>
            <p class="text-3xl font-bold text-yellow-600">{{ $reservations }}</p>
            <p class="text-sm text-gray-500 mt-1">Pending Reservations</p>
        </div>
    </div>
</div>

{{-- Overdue List --}}
@if($overdueBorrowings->count() > 0)
<div class="bg-red-50 border border-red-200 rounded-xl p-6">
    <h3 class="font-bold text-red-700 mb-4 flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        Overdue Books — Please return as soon as possible
    </h3>
    <div class="space-y-2">
        @foreach($overdueBorrowings as $b)
        <div class="flex justify-between text-sm bg-white rounded-lg p-3 border border-red-100">
            <span class="font-medium text-gray-800">{{ $b->ebook->title }}</span>
            <span class="text-red-600 font-medium">Due: {{ $b->due_date?->format('M d, Y') }}</span>
        </div>
        @endforeach
    </div>
</div>
@endif

@endsection
