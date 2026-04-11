@extends('layouts.member')
@section('title', 'My Dashboard')

@section('content')
<div class="space-y-6">

    {{-- Welcome + Plan Badge --}}
    <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl p-6 text-white">
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-2xl font-bold mb-1">Welcome back, {{ auth()->user()->member->full_name ?: auth()->user()->email }}!</h1>
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

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
            <div class="bg-white/20 rounded-xl p-4 text-center">
                <p class="text-2xl font-bold">{{ $accessCount }}</p>
                <p class="text-xs text-blue-100 mt-1">Ebooks Accessed</p>
            </div>
            <div class="bg-white/20 rounded-xl p-4 text-center">
                <p class="text-2xl font-bold">{{ $ebooksThisMonth }}</p>
                <p class="text-xs text-blue-100 mt-1">Read This Month</p>
            </div>
            <div class="bg-white/20 rounded-xl p-4 text-center">
                <p class="text-2xl font-bold">{{ $reviewsCount }}</p>
                <p class="text-xs text-blue-100 mt-1">Reviews Submitted</p>
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

    {{-- Reading Streak Widget --}}
    @php
        $member = auth()->user()->member;
        $currentStreak = $member->current_streak ?? 0;
        $longestStreak = $member->longest_streak ?? 0;
        $isCelebration = $currentStreak >= 7;
    @endphp
    <div class="bg-white rounded-xl shadow-sm border {{ $isCelebration ? 'border-yellow-300' : 'border-gray-100' }} p-6 relative overflow-hidden mb-6">
        {{-- Celebration background glow --}}
        @if($isCelebration)
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-yellow-100 rounded-full blur-3xl opacity-50"></div>
        @endif
        
        <div class="relative z-10 flex flex-col md:flex-row items-center gap-6">
            {{-- Flame Icon --}}
            <div class="flex-shrink-0 w-16 h-16 rounded-full flex items-center justify-center {{ $isCelebration ? 'bg-gradient-to-br from-yellow-300 to-orange-400 shadow-orange-200 shadow-lg' : ($currentStreak > 0 ? 'bg-orange-100 text-orange-500' : 'bg-gray-100 text-gray-400') }}">
                <span class="text-3xl">🔥</span>
            </div>
            
            {{-- Streak Details --}}
            <div class="flex-1 text-center md:text-left">
                @if($currentStreak > 0)
                    <h2 class="text-xl font-bold {{ $isCelebration ? 'text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-yellow-500' : 'text-gray-800' }}">
                        {{ $currentStreak }}-day reading streak!
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">Keep it up! You're on fire.</p>
                @else
                    <h2 class="text-xl font-bold text-gray-800">Start reading to build your streak!</h2>
                    <p class="text-sm text-gray-500 mt-1">Consistency is key to forming a habit.</p>
                @endif
                
                {{-- Progress Bar (Visual indicator out of 7 days) --}}
                <div class="mt-4 flex items-center gap-2 max-w-sm mx-auto md:mx-0">
                    @for($i = 1; $i <= 7; $i++)
                        @php
                            // Calculate display based on current streak modulo 7 (or full if exactly multiple of 7)
                            $filledIcons = $currentStreak % 7;
                            if ($filledIcons == 0 && $currentStreak > 0) $filledIcons = 7;
                            
                            $isActive = $i <= $filledIcons;
                        @endphp
                        <div class="flex-1 h-2 rounded-full {{ $isActive ? ($isCelebration ? 'bg-yellow-400' : 'bg-orange-400') : 'bg-gray-200' }}"></div>
                    @endfor
                </div>

                <div class="mt-3 text-xs font-semibold {{ $isCelebration ? 'text-yellow-600' : 'text-gray-400' }}">
                    Longest streak: {{ $longestStreak }} {{ \Illuminate\Support\Str::plural('day', $longestStreak) }}
                </div>
            </div>
        </div>
    </div>

    {{-- Editor's Choice / Spotlight Banner --}}
    @if(isset($spotlightEbook) && $spotlightEbook)
        <div class="bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-xl p-6 relative overflow-hidden shadow-sm flex flex-col md:flex-row items-center gap-6 mb-6">
            {{-- Decorative Background Badge --}}
            <svg class="absolute -right-10 -bottom-10 w-48 h-48 text-amber-100 opacity-50 transform rotate-12" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
            </svg>

            {{-- Cover --}}
            <div class="flex-shrink-0 relative z-10 w-24 sm:w-28 shadow-lg rounded-lg overflow-hidden border border-gray-100">
                @if($spotlightEbook->cover_image)
                    <img src="{{ Storage::url($spotlightEbook->cover_image) }}" alt="Cover" class="w-full h-auto object-cover aspect-[3/4]">
                @else
                    <div class="w-full aspect-[3/4] bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center">
                        <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13"/></svg>
                    </div>
                @endif
            </div>

            {{-- Info --}}
            <div class="flex-1 text-center md:text-left relative z-10">
                <span class="inline-flex items-center gap-1 bg-amber-100 text-amber-700 text-xs font-bold px-2.5 py-1 rounded-full mb-2 uppercase tracking-wide">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" /></svg>
                    Ebook of the Week
                </span>
                <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-1 leading-tight">{{ $spotlightEbook->title }}</h3>
                <p class="text-gray-600 text-sm mb-3 font-medium">{{ $spotlightEbook->authors->pluck('full_name')->join(', ') ?: 'Unknown Author' }}</p>
                <div class="flex flex-wrap items-center justify-center md:justify-start gap-2 mb-4">
                    <span class="bg-white border border-gray-200 text-gray-700 text-xs px-2 py-1 rounded-md">{{ $spotlightEbook->category->name ?? 'Uncategorized' }}</span>
                    @php $lvlColors = ['free'=>'bg-green-100 text-green-700','basic'=>'bg-blue-100 text-blue-700','premium'=>'bg-purple-100 text-purple-700']; @endphp
                    <span class="text-xs font-bold px-2 py-1 rounded-md {{ $lvlColors[$spotlightEbook->access_level] ?? 'bg-gray-100' }}">
                        {{ strtoupper($spotlightEbook->access_level) }}
                    </span>
                </div>
            </div>

            {{-- CTA --}}
            <div class="flex-shrink-0 relative z-10">
                <a href="{{ route('member.ebooks.show', $spotlightEbook) }}" class="inline-block w-full sm:w-auto text-center bg-gray-900 hover:bg-black text-white font-semibold px-6 py-2.5 rounded-lg transition-colors shadow-md hover:shadow-lg">
                    View Details
                </a>
            </div>
        </div>
    @endif

    {{-- Subscription Info Card --}}
    @if($subscription)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold text-gray-800">Subscription Status</h2>
            <a href="{{ route('member.subscriptions.index') }}" class="text-blue-600 text-sm hover:underline">Manage →</a>
        </div>
        <div class="flex items-center gap-4">
            <div class="flex-1">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-sm font-medium text-gray-700">{{ $plan->name ?? 'Unknown' }} Plan</span>
                    @if($subscription->expires_at)
                        <span class="text-xs text-gray-400">Expires {{ $subscription->expires_at->format('M d, Y') }}</span>
                    @else
                        <span class="text-xs text-gray-400">No expiry</span>
                    @endif
                </div>
                @if($daysLeft !== null && $daysTotal && $daysTotal > 0)
                    @php $pct = max(0, min(100, round(($daysLeft / $daysTotal) * 100))); @endphp
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full transition-all" style="width: {{ $pct }}%"></div>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">{{ $daysLeft }} of {{ $daysTotal }} days remaining ({{ $pct }}%)</p>
                @elseif($daysLeft === 0)
                    <div class="w-full bg-red-100 rounded-full h-2">
                        <div class="bg-red-500 h-2 rounded-full" style="width: 0%"></div>
                    </div>
                    <p class="text-xs text-red-500 mt-1">Subscription expired</p>
                @else
                    <div class="w-full bg-green-100 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: 100%"></div>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Active — no expiry date</p>
                @endif
            </div>
        </div>
    </div>
    @endif

    {{-- Upgrade Banner (only for free plan) --}}
    @if($plan && $plan->slug === 'free')
    <div class="bg-gradient-to-r from-purple-50 to-blue-50 border border-purple-200 rounded-xl p-4 flex items-center justify-between">
        <div>
            <p class="font-semibold text-gray-800 text-sm">Unlock more ebooks</p>
            <p class="text-xs text-gray-500">Upgrade to Basic or Premium for more access</p>
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
            <a href="{{ route('member.my-ebooks') }}" class="text-blue-600 text-sm hover:underline">View all →</a>
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
                    <p class="text-xs text-gray-400 truncate">{{ $access->ebook->authors->pluck('full_name')->join(', ') ?: 'Unknown' }}</p>
                </a>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Your Wishlist --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold text-gray-800">Your Wishlist</h2>
            <a href="{{ route('member.wishlist.index') }}" class="text-blue-600 text-sm hover:underline">View all →</a>
        </div>

        @if(isset($wishlistItems) && $wishlistItems->isEmpty())
            <div class="text-center py-10 text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-3 opacity-40 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                <p class="text-sm">Your wishlist is empty.</p>
                <a href="{{ route('member.ebooks.index') }}" class="mt-2 inline-block text-blue-600 text-sm hover:underline">Find books to read →</a>
            </div>
        @elseif(isset($wishlistItems))
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($wishlistItems as $item)
                <div class="group block relative bg-gray-50 rounded-lg p-2 border border-gray-100">
                    <a href="{{ route('member.ebooks.show', $item->id) }}" class="flex gap-3">
                        <div class="w-16 h-20 bg-gray-200 rounded flex-shrink-0 overflow-hidden shadow-sm">
                            @if($item->cover_image)
                                <img src="{{ asset('storage/' . $item->cover_image) }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-100 to-blue-200">
                                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13"/></svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0 py-1">
                            <p class="text-xs font-semibold text-gray-800 line-clamp-2 mb-1 group-hover:text-blue-600 transition-colors">{{ $item->title }}</p>
                            <p class="text-[10px] text-gray-400 truncate">{{ $item->authors->pluck('full_name')->join(', ') ?: 'Unknown' }}</p>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection
