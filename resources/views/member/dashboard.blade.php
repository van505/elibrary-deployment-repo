@extends('layouts.member')
@section('title', 'My Dashboard')

@push('breadcrumbs')
<nav class="flex items-center text-sm">
    <span class="text-gray-700 font-medium">My Dashboard</span>
</nav>
@endpush

@section('content')
<div class="space-y-6">

    {{-- 🎉 Welcome Onboarding Banner --}}
    @if(session('welcome_onboard'))
    <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-2xl p-5 text-white flex items-center gap-4 shadow-md">
        <div class="text-4xl">🎉</div>
        <div class="flex-1">
            <h2 class="font-bold text-lg">Welcome to ELibrary, {{ auth()->user()->member->first_name ?: 'Reader' }}!</h2>
            <p class="text-green-100 text-sm">Your account is all set up. Start exploring books below!</p>
        </div>
        <a href="{{ route('member.ebooks.index') }}" class="bg-white text-green-700 font-bold text-sm px-4 py-2 rounded-xl hover:bg-green-50 transition-colors whitespace-nowrap">Browse Books</a>
    </div>
    @endif

    {{-- Welcome + Plan Badge --}}
    <div class="bg-gradient-to-r from-indigo-700 to-blue-500 rounded-xl shadow-md p-6 text-white mb-6">
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
                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $color }} shadow-sm">
                    {{ strtoupper($plan->name) }} PLAN
                </span>
            @else
                <a href="{{ route('member.subscriptions.index') }}" class="bg-white text-blue-600 text-xs font-bold px-3 py-1 rounded-full hover:bg-blue-50 transition-colors shadow-sm">GET A PLAN</a>
            @endif
        </div>
    </div>

    {{-- Extracted Metrics Row --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-md border border-slate-200 p-4 flex items-center justify-between">
            <div>
                <p class="text-3xl font-bold text-gray-800">{{ $accessCount }}</p>
                <p class="text-xs text-gray-500 mt-1 font-medium">Ebooks Accessed</p>
            </div>
            <div class="bg-indigo-50 text-indigo-600 p-3 rounded-xl flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-md border border-slate-200 p-4 flex items-center justify-between">
            <div>
                <p class="text-3xl font-bold text-gray-800">{{ $ebooksThisMonth }}</p>
                <p class="text-xs text-gray-500 mt-1 font-medium">Read This Month</p>
            </div>
            <div class="bg-emerald-50 text-emerald-600 p-3 rounded-xl flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-md border border-slate-200 p-4 flex items-center justify-between">
            <div>
                <p class="text-3xl font-bold text-gray-800">{{ $reviewsCount }}</p>
                <p class="text-xs text-gray-500 mt-1 font-medium">Reviews Submitted</p>
            </div>
            <div class="bg-amber-50 text-amber-600 p-3 rounded-xl flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-md border border-slate-200 p-4 flex items-center justify-between">
            <div>
                @if($daysLeft !== null)
                    <p class="text-3xl font-bold text-gray-800">{{ $daysLeft }}d</p>
                    <p class="text-xs text-gray-500 mt-1 font-medium">Days Left</p>
                @else
                    <p class="text-3xl font-bold text-gray-800">∞</p>
                    <p class="text-xs text-gray-500 mt-1 font-medium">Never Expires</p>
                @endif
            </div>
            <div class="bg-purple-50 text-purple-600 p-3 rounded-xl flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Reading Streak Widget --}}
        @php
            $member = auth()->user()->member;
            $currentStreak = $member->current_streak ?? 0;
            $longestStreak = $member->longest_streak ?? 0;
            $isCelebration = $currentStreak >= 7;
        @endphp
        <div class="bg-white rounded-xl shadow-md border {{ $isCelebration ? 'border-yellow-300' : 'border-slate-200' }} p-6 relative overflow-hidden h-full flex flex-col justify-center">
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
            <div class="bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-xl p-6 relative overflow-hidden shadow-sm flex flex-col sm:flex-row items-center gap-6 h-full">
                {{-- Decorative Background Badge --}}
                <svg class="absolute -right-10 -bottom-10 w-48 h-48 text-amber-100 opacity-50 transform rotate-12" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>

                {{-- Cover --}}
                <div class="flex-shrink-0 relative z-10 w-24 shadow-lg rounded-lg overflow-hidden border border-gray-100">
                    @if($spotlightEbook->cover_image)
                        <img src="{{ Storage::url($spotlightEbook->cover_image) }}" alt="Cover" class="w-full h-auto object-cover aspect-[3/4]">
                    @else
                        <div class="w-full aspect-[3/4] bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center">
                            <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13"/></svg>
                        </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="flex-1 text-center sm:text-left relative z-10 flex flex-col justify-center h-full">
                    <span class="inline-flex items-center gap-1 bg-amber-100 text-amber-700 text-[10px] font-bold px-2.5 py-1 rounded-full mb-2 uppercase tracking-wide w-fit mx-auto sm:mx-0">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" /></svg>
                        Ebook of the Week
                    </span>
                    <h3 class="text-xl font-bold text-gray-900 mb-1 leading-tight line-clamp-2">{{ $spotlightEbook->title }}</h3>
                    <p class="text-gray-600 text-xs mb-3 font-medium line-clamp-1">{{ $spotlightEbook->authors->pluck('full_name')->join(', ') ?: 'Unknown Author' }}</p>
                    
                    <div class="mt-auto">
                        <a href="{{ route('member.ebooks.show', $spotlightEbook) }}" class="inline-block w-full text-center bg-gray-900 hover:bg-black text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors shadow-sm hover:shadow-md">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Featured Collections (Horizontal Scroll) --}}
    @if(isset($featuredCollections) && $featuredCollections->isNotEmpty())
        <div class="mb-8 bg-white rounded-xl shadow-md border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-900 border-l-4 border-yellow-400 pl-3">Featured Series</h2>
                <a href="{{ route('member.collections.index') }}" class="text-sm font-semibold text-blue-600 hover:underline">View All &rarr;</a>
            </div>
            
            <div class="flex overflow-x-auto gap-5 pb-4 snap-x hide-scrollbar">
                @foreach($featuredCollections as $collection)
                    <a href="{{ route('member.collections.show', $collection->slug) }}" class="snap-start flex-shrink-0 w-72 sm:w-80 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition group">
                        <div class="h-32 bg-gray-200 relative overflow-hidden">
                            @if($collection->cover_image)
                                <img src="{{ Storage::url($collection->cover_image) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center p-4">
                                    <span class="text-white font-bold opacity-80 text-center leading-tight">{{ $collection->name }}</span>
                                </div>
                            @endif
                            <div class="absolute bottom-2 right-2 bg-black/70 backdrop-blur-sm text-white text-xs font-bold px-2 py-0.5 rounded shadow">
                                {{ $collection->ebooks_count }} {{ Str::plural('Book', $collection->ebooks_count) }}
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-bold text-gray-900 truncate">{{ $collection->name }}</h3>
                            <p class="text-xs text-gray-500 mt-1 line-clamp-2 min-h-[2rem]">{{ $collection->description }}</p>
                        </div>
                    </a>
                @endforeach
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

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Recently Accessed --}}
        <div class="bg-white rounded-xl shadow-md border border-slate-200 p-6 flex flex-col h-full">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-semibold text-gray-800">Recently Accessed</h2>
                <a href="{{ route('member.my-ebooks') }}" class="text-blue-600 text-sm hover:underline">View all →</a>
            </div>

            @if($recentAccess->isEmpty())
                <div class="text-center py-10 text-gray-400 my-auto">
                    <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13"/></svg>
                    <p class="text-sm">No ebooks accessed yet.</p>
                    <a href="{{ route('member.ebooks.index') }}" class="mt-2 inline-block text-blue-600 text-sm hover:underline">Browse ebooks →</a>
                </div>
            @else
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($recentAccess->take(3) as $access)
                    <a href="{{ route('member.ebooks.read', $access->ebook->id) }}" class="group block text-center">
                        <div class="mx-auto h-48 w-32 bg-gray-100 rounded-md shadow-sm overflow-hidden mb-3">
                            @if($access->ebook->cover_image)
                                <img src="{{ asset('storage/' . $access->ebook->cover_image) }}" alt="{{ $access->ebook->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-100 to-blue-200">
                                    <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13"/></svg>
                                </div>
                            @endif
                        </div>
                        <p class="text-xs font-medium text-gray-700 truncate px-2">{{ $access->ebook->title }}</p>
                        <p class="text-xs text-gray-400 truncate px-2">{{ $access->ebook->authors->pluck('full_name')->join(', ') ?: 'Unknown' }}</p>
                    </a>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Your Wishlist --}}
        <div class="bg-white rounded-xl shadow-md border border-slate-200 p-6 flex flex-col h-full">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-semibold text-gray-800">Your Wishlist</h2>
                <a href="{{ route('member.wishlist.index') }}" class="text-blue-600 text-sm hover:underline">View all →</a>
            </div>

            @if(isset($wishlistItems) && $wishlistItems->isEmpty())
                <div class="text-center py-10 text-gray-400 my-auto">
                    <svg class="w-12 h-12 mx-auto mb-3 opacity-40 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    <p class="text-sm">Your wishlist is empty.</p>
                    <a href="{{ route('member.ebooks.index') }}" class="mt-2 inline-block text-blue-600 text-sm hover:underline">Find books to read →</a>
                </div>
            @elseif(isset($wishlistItems))
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($wishlistItems->take(3) as $item)
                    <a href="{{ route('member.ebooks.show', $item->id) }}" class="group block text-center">
                        <div class="mx-auto h-48 w-32 bg-gray-100 rounded-md shadow-sm overflow-hidden mb-3">
                            @if($item->cover_image)
                                <img src="{{ asset('storage/' . $item->cover_image) }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-100 to-blue-200">
                                    <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13"/></svg>
                                </div>
                            @endif
                        </div>
                        <p class="text-xs font-medium text-gray-700 truncate px-2">{{ $item->title }}</p>
                        <p class="text-xs text-gray-400 truncate px-2">{{ $item->authors->pluck('full_name')->join(', ') ?: 'Unknown' }}</p>
                    </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

    {{-- ── Recommended Ebooks ───────────────────────────────────────────────── --}}
    @if(isset($recommendedEbooks) && $recommendedEbooks->isNotEmpty())
    <div class="bg-white rounded-xl shadow-md border border-slate-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="font-semibold text-gray-800">
                    @if(isset($hasPreferences) && $hasPreferences)
                        ✨ Based on Your Interests
                    @else
                        🆕 New Arrivals
                    @endif
                </h2>
                @if(isset($hasPreferences) && $hasPreferences)
                    <p class="text-xs text-gray-400 mt-0.5">Books matching your selected categories</p>
                @else
                    <p class="text-xs text-gray-400 mt-0.5">Discover our latest additions</p>
                @endif
            </div>
            <a href="{{ route('member.ebooks.index') }}" class="text-blue-600 text-sm hover:underline font-medium">Browse All →</a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4">
            @foreach($recommendedEbooks as $ebook)
            <a href="{{ route('member.ebooks.show', $ebook->id) }}" class="group block">
                <div class="aspect-[3/4] bg-gray-100 rounded-lg overflow-hidden mb-2 shadow-sm group-hover:shadow-md transition-shadow">
                    @if($ebook->cover_image)
                        <img src="{{ Storage::url($ebook->cover_image) }}" alt="{{ $ebook->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center">
                            <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13"/></svg>
                        </div>
                    @endif
                </div>
                <p class="text-xs font-semibold text-gray-800 truncate group-hover:text-blue-600 transition-colors">{{ $ebook->title }}</p>
                <p class="text-[10px] text-gray-400 truncate mt-0.5">{{ $ebook->authors->pluck('full_name')->join(', ') ?: 'Unknown' }}</p>
            </a>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection
