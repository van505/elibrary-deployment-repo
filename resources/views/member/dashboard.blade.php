@extends('layouts.member')
@section('title', 'My Dashboard')

@section('content')
<div class="space-y-6">

    {{-- Welcome + Plan Badge --}}
    <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl p-6 text-white">
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-2xl font-bold mb-1">Welcome back, {{ auth()->user()->name }}!</h1>
                <p class="text-blue-100 text-sm">Your digital reading hub</p>
            </div>
            @if($plan)
                @php
                    $planColors = ['free' => 'bg-gray-200 text-gray-700', 'basic' => 'bg-blue-200 text-blue-800', 'premium' => 'bg-purple-200 text-purple-800'];
                    $color = $planColors[$plan->slug] ?? 'bg-gray-200 text-gray-700';
                @endphp
                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $color }}">
                    {{ strtoupper($plan->name) }} PLAN
                </span>
            @else
                <a href="{{ route('member.subscriptions.index') }}" class="bg-white text-blue-600 text-xs font-bold px-3 py-1 rounded-full hover:bg-blue-50 transition-colors">GET A PLAN</a>
            @endif
        </div>

        <div class="grid grid-cols-3 gap-4 mt-6">
            <div class="bg-white/20 rounded-xl p-4 text-center">
                <p class="text-2xl font-bold">{{ $accessCount }}</p>
                <p class="text-xs text-blue-100 mt-1">Ebooks Accessed</p>
            </div>
            <div class="bg-white/20 rounded-xl p-4 text-center">
                <p class="text-2xl font-bold">{{ $plan ? ($plan->ebook_limit === -1 ? '∞' : $plan->ebook_limit) : 0 }}</p>
                <p class="text-xs text-blue-100 mt-1">Plan Limit</p>
            </div>
            <div class="bg-white/20 rounded-xl p-4 text-center">
                @if($daysLeft !== null)
                    <p class="text-2xl font-bold">{{ $daysLeft }}d</p>
                    <p class="text-xs text-blue-100 mt-1">Days Left</p>
                @else
                    <p class="text-2xl font-bold">∞</p>
                    <p class="text-xs text-blue-100 mt-1">Never Expires</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Upgrade Banner (only for free plan) --}}
    @if($plan && $plan->slug === 'free')
    <div class="bg-gradient-to-r from-purple-50 to-blue-50 border border-purple-200 rounded-xl p-4 flex items-center justify-between">
        <div>
            <p class="font-semibold text-gray-800 text-sm">Unlock more ebooks</p>
            <p class="text-xs text-gray-500">Upgrade to Basic (₱99/mo) or Premium (₱199/mo) for more access</p>
        </div>
        <a href="{{ route('member.subscriptions.index') }}" class="bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors whitespace-nowrap">
            Upgrade Plan
        </a>
    </div>
    @endif

    {{-- Recently Accessed --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold text-gray-800">Recently Accessed</h2>
            <a href="{{ route('member.ebooks.index') }}" class="text-blue-600 text-sm hover:underline">Browse all</a>
        </div>

        @if($recentAccess->isEmpty())
            <div class="text-center py-10 text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13"/></svg>
                <p class="text-sm">No ebooks accessed yet.</p>
                <a href="{{ route('member.ebooks.index') }}" class="mt-2 inline-block text-blue-600 text-sm hover:underline">Browse ebooks →</a>
            </div>
        @else
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                @foreach($recentAccess as $access)
                <a href="{{ route('member.ebooks.read', $access->ebook->id) }}" class="group block">
                    <div class="aspect-[3/4] bg-gray-100 rounded-lg overflow-hidden mb-2">
                        @if($access->ebook->cover_image)
                            <img src="{{ asset('storage/' . $access->ebook->cover_image) }}" alt="{{ $access->ebook->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-100 to-blue-200">
                                <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13"/></svg>
                            </div>
                        @endif
                    </div>
                    <p class="text-xs font-medium text-gray-700 truncate">{{ $access->ebook->title }}</p>
                </a>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection
