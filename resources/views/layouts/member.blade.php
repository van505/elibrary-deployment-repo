<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ELibrary — @yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
    @stack('styles')
</head>
<body class="bg-slate-50 flex h-screen overflow-hidden">

    {{-- ========== OVERLAY (mobile) ========== --}}
    <div id="sidebarOverlay"
         class="fixed inset-0 bg-black/50 z-20 hidden md:hidden"
         onclick="closeSidebar()"></div>

    {{-- ========== SIDEBAR ========== --}}
    <aside id="sidebar"
           class="fixed md:relative z-30 w-64 bg-slate-800 flex flex-col flex-shrink-0 h-screen overflow-y-auto
                  -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">

        {{-- Logo --}}
        <div class="flex items-center gap-3 px-6 py-5 border-b border-slate-700">
            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <span class="text-white font-bold text-lg">ELibrary</span>
            <span class="text-xs text-green-400 font-medium ml-auto">Member</span>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-3 py-4 space-y-6 overflow-y-auto">
            @php
            $sections = [
                'LIBRARY' => [
                    ['route' => 'member.dashboard',           'label' => 'Dashboard',          'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                    ['route' => 'member.ebooks.index',        'label' => 'Browse Ebooks',      'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                    ['route' => 'member.collections.index',   'label' => 'Collections',        'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
                ],
                'MY ACCOUNT' => [
                    ['route' => 'member.subscriptions.index', 'label' => 'My Subscription',   'icon' => 'M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z'],
                    ['route' => 'member.my-ebooks',           'label' => 'Reading History',   'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['route' => 'member.reviews.index',       'label' => 'My Reviews',         'icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z'],
                    ['route' => 'member.bookmarks.index',     'label' => 'My Bookmarks',          'icon' => 'M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z'],
                ],
            ];
            @endphp

            @foreach($sections as $title => $links)
                <div class="mb-4">
                    <h3 class="px-3 text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">{{ $title }}</h3>
                    <ul class="space-y-1">
                        @foreach($links as $link)
                            @php $active = request()->routeIs($link['route'] . '*'); @endphp
                            <li>
                                <a href="{{ route($link['route']) }}"
                                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                                          {{ $active ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $link['icon'] }}"/>
                                    </svg>
                                    {{ $link['label'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach

            {{-- BOTTOM LINKS --}}
            <div class="mt-8 mb-4 border-t border-slate-700 pt-4">
                <ul class="space-y-1">
                    <li>
                        <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-slate-300 hover:bg-slate-700 hover:text-white transition-colors">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Help
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </aside>


    {{-- ========== MAIN CONTENT ========== --}}
    <div class="flex-1 flex flex-col h-screen overflow-hidden min-w-0">

        {{-- Top Navbar --}}
        <header class="bg-white border-b border-gray-100 px-4 md:px-6 py-4 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center min-w-0">
                {{-- Hamburger (mobile only) --}}
                <button id="hamburgerBtn"
                        onclick="toggleSidebar()"
                        class="md:hidden p-2 mr-3 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors flex-shrink-0"
                        aria-label="Toggle sidebar">
                    <span id="hamburgerIcon" class="text-xl leading-none">☰</span>
                </button>
                @stack('breadcrumbs')
            </div>

            {{-- Global Search Bar --}}
            <div class="hidden md:block flex-1 max-w-md mx-4">
                <form action="{{ route('member.ebooks.index') }}" method="GET" class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400 group-focus-within:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-xl leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent sm:text-sm transition-all shadow-sm"
                           placeholder="Search ebooks, authors, or categories..."
                           autocomplete="off">
                </form>
            </div>

            <div class="flex items-center gap-3">
                {{-- Wishlist Icon --}}
                <a href="{{ route('member.wishlist.index') }}" class="p-2 rounded-lg text-gray-500 hover:text-red-500 hover:bg-red-50 transition-colors" title="My Wishlist" aria-label="Wishlist">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </a>

                {{-- Notification Bell --}}
                <div class="relative">
                    <button onclick="toggleNotifications()" class="relative p-2 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors" aria-label="Notifications">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        @if(isset($unreadNotificationsCount) && $unreadNotificationsCount > 0)
                            <span class="absolute -top-0.5 -right-0.5 w-5 h-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center">
                                {{ $unreadNotificationsCount > 9 ? '9+' : $unreadNotificationsCount }}
                            </span>
                        @endif
                    </button>
                    {{-- Notification Dropdown --}}
                    <div id="notificationDropdown"
                         class="hidden absolute right-0 top-12 w-80 bg-white rounded-xl shadow-xl border border-gray-100 z-50">
                        <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                            <h3 class="font-semibold text-gray-800 text-sm">Notifications</h3>
                            @if(isset($unreadNotificationsCount) && $unreadNotificationsCount > 0)
                                <form action="{{ route('member.notifications.read-all') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-xs text-blue-600 hover:underline">Mark all read</button>
                                </form>
                            @endif
                        </div>
                        <div class="max-h-72 overflow-y-auto divide-y divide-gray-50">
                            @if(isset($recentNotifications) && $recentNotifications->isNotEmpty())
                                @foreach($recentNotifications as $n)
                                <form action="{{ route('member.notifications.read', $n->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-3 hover:bg-gray-50 transition-colors {{ $n->is_read ? 'opacity-60' : '' }}">
                                        <p class="text-sm text-gray-800 {{ !$n->is_read ? 'font-medium' : '' }}">{{ $n->message }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $n->created_at->diffForHumans() }}</p>
                                    </button>
                                </form>
                                @endforeach
                            @else
                                <div class="px-4 py-8 text-center text-gray-400 text-sm">No notifications yet.</div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Member Avatar Dropdown --}}
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <button @click="open = !open"
                            class="flex items-center gap-2 cursor-pointer focus:outline-none group">
                        <span class="hidden sm:block text-sm text-gray-600 font-medium group-hover:text-gray-900 transition-colors">
                            {{ auth()->user()->member?->full_name ?: (auth()->user()->email ?? '') }}
                        </span>
                        @if(auth()->user()->member?->avatar)
                            <img src="{{ Storage::url(auth()->user()->member->avatar) }}"
                                 alt="Avatar"
                                 class="w-8 h-8 rounded-full object-cover ring-2 ring-transparent group-hover:ring-blue-200 transition-all">
                        @else
                            <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-xs font-bold shadow-sm ring-2 ring-transparent group-hover:ring-blue-200 transition-all">
                                {{ strtoupper(substr(auth()->user()->member?->full_name ?: auth()->user()->email ?? 'M', 0, 1)) }}
                            </div>
                        @endif
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    {{-- Dropdown Panel --}}
                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95 translate-y-[-4px]"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 top-[calc(100%+8px)] w-[220px] bg-white rounded-xl border border-gray-100 shadow-[0_10px_25px_-5px_rgba(0,0,0,0.12)] z-50 overflow-hidden"
                         style="display: none;"
                         role="menu">
                        
                        {{-- Header --}}
                        <div class="px-4 py-3 bg-gray-50 border-b border-gray-100 flex items-center gap-3">
                            @if(auth()->user()->member?->avatar)
                                <img src="{{ Storage::url(auth()->user()->member->avatar) }}" alt="Avatar" class="w-8 h-8 rounded-full object-cover flex-shrink-0">
                            @else
                                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                    {{ strtoupper(substr(auth()->user()->member?->full_name ?: auth()->user()->email ?? 'M', 0, 1)) }}
                                </div>
                            @endif
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->member?->full_name ?: (auth()->user()->email ?? 'Member') }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                            </div>
                        </div>

                        {{-- Menu Items --}}
                        <div class="py-1">
                            <a href="{{ route('member.profile.edit') }}"
                               class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-150"
                               role="menuitem">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                                My Profile
                            </a>
                            
                            <a href="{{ route('member.subscriptions.index') }}"
                               class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-150"
                               role="menuitem">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                                My Subscription
                            </a>

                            {{-- TODO: create member settings page --}}
                            <a href="{{ route('member.profile.edit') }}"
                               class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-150"
                               role="menuitem">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Settings
                            </a>
                        </div>

                        <div class="border-t border-gray-100 my-1"></div>

                        {{-- Logout --}}
                        <div class="py-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors duration-150"
                                        role="menuitem">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/></svg>
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{-- Global Active Announcements --}}
        @if(isset($globalActiveAnnouncements) && $globalActiveAnnouncements->count() > 0)
            <div id="announcement-container" class="flex flex-col">
                @foreach($globalActiveAnnouncements as $ann)
                    @php
                        $annColors = [
                            'info' => 'bg-blue-600 text-white',
                            'warning' => 'bg-yellow-500 text-yellow-900',
                            'success' => 'bg-green-600 text-white',
                            'danger' => 'bg-red-600 text-white',
                        ];
                        
                        $annIcons = [
                            'info' => '<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
                            'warning' => '<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>',
                            'success' => '<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
                            'danger' => '<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
                        ];
                    @endphp
                    <div id="announcement-{{ $ann->id }}" style="display: none;" class="{{ $annColors[$ann->type] ?? 'bg-blue-600 text-white' }} px-4 py-3 flex items-center justify-between shadow-sm relative z-20">
                        <div class="flex items-center gap-3">
                            {!! $annIcons[$ann->type] ?? $annIcons['info'] !!}
                            <p class="text-sm">
                                <span class="font-bold">{{ $ann->title }}:</span> {{ $ann->message }}
                            </p>
                        </div>
                        <button onclick="dismissAnnouncement({{ $ann->id }})" class="p-1 rounded-md opacity-80 hover:opacity-100 hover:bg-black/10 transition-colors flex-shrink-0" aria-label="Dismiss">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                @endforeach
            </div>
            
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const announcements = @json($globalActiveAnnouncements->pluck('id'));
                    announcements.forEach(id => {
                        if (!localStorage.getItem('dismissed_announcement_' + id)) {
                            const el = document.getElementById('announcement-' + id);
                            if (el) el.style.display = 'flex';
                        }
                    });
                });

                function dismissAnnouncement(id) {
                    localStorage.setItem('dismissed_announcement_' + id, 'true');
                    const el = document.getElementById('announcement-' + id);
                    if (el) {
                        el.style.opacity = '0';
                        setTimeout(() => el.remove(), 300);
                    }
                }
            </script>
        @endif

        {{-- Subscription Expiry Banner --}}
        @if(!request()->routeIs('member.subscriptions.*'))
        @php
            $member = auth()->user()->member;
            $subscription = $member ? $member->subscriptions()->latest()->first() : null;
            $showBanner = false;
            
            if (!$subscription || ($subscription->status !== 'active' && !$subscription->expires_at)) {
                // No subscription at all
                $showBanner = true;
                $bannerClass = 'bg-blue-50 border-blue-200 text-blue-800';
                $bannerIconClass = 'text-blue-500';
                $bannerMsg = "You don't have an active subscription. Browse our plans to get started.";
                $bannerBtn = 'See Plans';
                $btnClass = 'bg-blue-600 hover:bg-blue-700 text-white shadow-sm';
            } else {
                if ($subscription->status !== 'active' || ($subscription->expires_at && \Carbon\Carbon::parse($subscription->expires_at)->isPast())) {
                    // Expired
                    $showBanner = true;
                    $bannerClass = 'bg-red-900 border-red-800 text-red-50';
                    $bannerIconClass = 'text-red-400';
                    $bannerMsg = "Your subscription has expired. Subscribe again to continue reading.";
                    $bannerBtn = 'Subscribe Now';
                    $btnClass = 'bg-red-500 hover:bg-red-600 text-white shadow-sm shadow-red-900/50';
                } elseif ($subscription->expires_at) {
                    $daysLeft = now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($subscription->expires_at)->startOfDay(), false);
                    
                    if ($daysLeft <= 1) {
                        $showBanner = true;
                        $bannerClass = 'bg-red-50 border-red-200 text-red-800';
                        $bannerIconClass = 'text-red-500';
                        $bannerMsg = "Your subscription expires TODAY! Renew immediately to avoid losing access.";
                        $bannerBtn = 'Renew Now';
                        $btnClass = 'bg-red-600 hover:bg-red-700 text-white shadow-sm shadow-red-200';
                    } elseif ($daysLeft <= 3) {
                        $showBanner = true;
                        $bannerClass = 'bg-orange-50 border-orange-200 text-orange-800';
                        $bannerIconClass = 'text-orange-500';
                        $bannerMsg = "Your subscription expires in {$daysLeft} days! Renew now to keep your access.";
                        $bannerBtn = 'Renew Now';
                        $btnClass = 'bg-orange-500 hover:bg-orange-600 text-white shadow-sm shadow-orange-200';
                    } elseif ($daysLeft <= 7) {
                        $showBanner = true;
                        $bannerClass = 'bg-yellow-50 border-yellow-200 text-yellow-800';
                        $bannerIconClass = 'text-yellow-600';
                        $bannerMsg = "Your subscription expires in {$daysLeft} days. Consider renewing soon.";
                        $bannerBtn = 'View Plans';
                        $btnClass = 'bg-yellow-500 hover:bg-yellow-600 text-white shadow-sm shadow-yellow-200';
                    }
                }
            }
        @endphp

        @if($showBanner)
            <div id="subscriptionExpirBanner" style="display: none;" class="{{ $bannerClass }} border-b px-4 py-3 flex items-center justify-between shadow-sm relative z-10 transition-opacity duration-300">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0 {{ $bannerIconClass ?? '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-sm font-medium">{{ $bannerMsg }}</p>
                </div>
                <div class="flex items-center gap-4 flex-shrink-0">
                    <a href="{{ route('member.subscriptions.index') }}" class="{{ $btnClass }} px-3 py-1.5 rounded-lg text-xs font-bold whitespace-nowrap transition-colors">
                        {{ $bannerBtn }}
                    </a>
                    <button onclick="dismissSubBanner()" class="p-1 hover:bg-black/10 rounded-md transition-colors" aria-label="Dismiss">
                        <svg class="w-4 h-4 opacity-70 hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>
            
            <script>
                if(!sessionStorage.getItem('hideSubBanner')) {
                    document.getElementById('subscriptionExpirBanner').style.display = 'flex';
                }
                function dismissSubBanner() {
                    const banner = document.getElementById('subscriptionExpirBanner');
                    if(banner) {
                        banner.style.opacity = '0';
                        setTimeout(() => banner.style.display = 'none', 300);
                        sessionStorage.setItem('hideSubBanner', 'true');
                    }
                }
            </script>
        @endif
        @endif

        {{-- Content --}}
        <main class="flex-1 overflow-y-auto p-4 md:p-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    {{-- Sidebar + Notification JS --}}
    <script>
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const hamburgerIcon = document.getElementById('hamburgerIcon');
    let sidebarOpen = false;

    function openSidebar() {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
        hamburgerIcon.textContent = '✕';
        sidebarOpen = true;
    }

    function closeSidebar() {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
        hamburgerIcon.textContent = '☰';
        sidebarOpen = false;
    }

    function toggleSidebar() {
        sidebarOpen ? closeSidebar() : openSidebar();
    }

    const notifDropdown = document.getElementById('notificationDropdown');
    function toggleNotifications() {
        notifDropdown.classList.toggle('hidden');
    }
    // Close notification dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('#notificationDropdown') && !e.target.closest('[onclick="toggleNotifications()"]')) {
            notifDropdown.classList.add('hidden');
        }
    });
    </script>

    @stack('scripts')
</body>
</html>
