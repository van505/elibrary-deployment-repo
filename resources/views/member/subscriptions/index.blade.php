@extends('layouts.member')
@section('title', 'My Subscription')

@section('content')
<div class="space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-gray-800">Choose Your Plan</h1>
        <p class="text-gray-500 text-sm mt-1">Select a plan that fits your reading habits</p>
    </div>

    {{-- Current Plan Banner --}}
    @if($currentSubscription)
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 flex items-center gap-3">
        <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p class="text-sm text-blue-700">
            You are currently on the <strong>{{ $currentSubscription->plan->name }}</strong> plan.
            @if($currentSubscription->expires_at)
                Expires {{ $currentSubscription->expires_at->format('M d, Y') }}.
            @else
                No expiry date.
            @endif
        </p>
    </div>
    @endif

    {{-- Pricing Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        @foreach($plans as $plan)
        @php
            $isCurrent = $currentSubscription && $currentSubscription->plan_id === $plan->id;
            $isPremium = $plan->slug === 'premium';
            $borderClass = $isPremium ? 'border-purple-400 ring-2 ring-purple-200' : 'border-gray-200';
            $btnClass = match($plan->slug) {
                'premium' => 'bg-purple-600 hover:bg-purple-700',
                'basic'   => 'bg-blue-600 hover:bg-blue-700',
                default   => 'bg-gray-600 hover:bg-gray-700',
            };
        @endphp
        <div class="relative bg-white rounded-2xl shadow-sm border {{ $borderClass }} p-6 flex flex-col">

            @if($isPremium)
                <span class="absolute -top-3 left-1/2 -translate-x-1/2 bg-purple-600 text-white text-xs font-bold px-3 py-1 rounded-full">Most Popular</span>
            @endif

            @if($isCurrent)
                <span class="absolute top-4 right-4 bg-green-100 text-green-700 text-xs font-semibold px-2 py-0.5 rounded-full">Current Plan</span>
            @endif

            <div class="mb-4">
                <h3 class="text-xl font-bold text-gray-800">{{ $plan->name }}</h3>
                <div class="mt-2">
                    <span class="text-3xl font-bold text-gray-900">₱{{ number_format($plan->price, 0) }}</span>
                    <span class="text-gray-500 text-sm">/month</span>
                </div>
                <p class="text-sm text-gray-500 mt-2">{{ $plan->description }}</p>
            </div>

            <ul class="space-y-2 mb-6 flex-1">
                <li class="flex items-center gap-2 text-sm text-gray-600">
                    <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ $plan->ebook_limit === -1 ? 'Unlimited ebooks' : 'Up to ' . $plan->ebook_limit . ' ebooks' }}
                </li>
                <li class="flex items-center gap-2 text-sm text-gray-600">
                    <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    @if($plan->slug === 'free') Free ebooks only
                    @elseif($plan->slug === 'basic') Free + Basic ebooks
                    @else All access levels @endif
                </li>
                <li class="flex items-center gap-2 text-sm text-gray-600">
                    <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    PDF, EPUB &amp; MP3 formats
                </li>
            </ul>

            @if($isCurrent)
                <button disabled class="w-full bg-gray-100 text-gray-400 py-2.5 rounded-xl text-sm font-semibold cursor-not-allowed">Current Plan</button>
            @else
                <form action="{{ route('member.subscriptions.subscribe') }}" method="POST" class="space-y-2">
                    @csrf
                    <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                    @if($plan->price > 0)
                    <select name="payment_method" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none mb-2">
                        <option value="cash">Cash</option>
                        <option value="gcash">GCash</option>
                        <option value="maya">Maya</option>
                        <option value="credit_card">Credit Card</option>
                    </select>
                    @endif
                    <button type="submit" class="w-full {{ $btnClass }} text-white py-2.5 rounded-xl text-sm font-semibold transition-colors">
                        {{ $plan->price === 0.0 ? 'Get Started Free' : 'Subscribe for ₱' . number_format($plan->price, 0) }}
                    </button>
                </form>
            @endif
        </div>
        @endforeach

    </div>
</div>
@endsection
