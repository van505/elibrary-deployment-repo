<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ELibrary Admin — @yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('styles')
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">

    {{-- ========== OVERLAY (mobile) ========== --}}
    <div x-show="sidebarOpen"
         x-transition.opacity
         class="fixed inset-0 bg-black/50 z-20 md:hidden"
         @click="sidebarOpen = false"
         style="display: none;"></div>

    {{-- ========== SIDEBAR ========== --}}
    <aside class="fixed md:relative z-30 w-64 bg-indigo-950 border-r border-indigo-900 flex flex-col flex-shrink-0 h-screen overflow-y-auto sidebar-scroll transition-transform duration-300 ease-in-out md:translate-x-0 shadow-xl"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

        {{-- Logo --}}
        <div class="flex items-center gap-3 px-6 py-5 border-b border-indigo-800/50">
            <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center shadow-inner">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <span class="text-white font-bold text-lg tracking-wide">ELibrary</span>
            <span class="text-[10px] uppercase tracking-wider text-indigo-300 font-bold bg-indigo-800/50 px-2 py-0.5 rounded-full ml-auto">Admin</span>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-3 py-4 space-y-6">
            @php
            $sections = [
                'LIBRARY' => [
                    ['route' => 'admin.dashboard',                'label' => 'Dashboard',         'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                    ['route' => 'admin.ebooks.index',             'label' => 'Ebooks',             'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                    ['route' => 'admin.collections.index',        'label' => 'Collections',        'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
                    ['route' => 'admin.categories.index',         'label' => 'Categories',         'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z'],
                    ['route' => 'admin.authors.index',            'label' => 'Authors',            'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                ],
                'COMMUNITY' => [
                    ['route' => 'admin.members.index',            'label' => 'Members',            'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
                    ['route' => 'admin.reviews.index',            'label' => 'Reviews',            'icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z'],
                ],
                'BILLING' => [
                    ['route' => 'admin.subscriptions.index',      'label' => 'Subscriptions',      'icon' => 'M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z'],
                    ['route' => 'admin.transactions.index',       'label' => 'Transactions',       'icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z'],
                ],
                'ADMIN' => [
                    ['route' => 'admin.announcements.index',      'label' => 'Announcements',      'icon' => 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z'],
                    ['route' => 'admin.reports.index',            'label' => 'Reports',            'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                ],
            ];
            @endphp

            @foreach($sections as $title => $links)
                <div class="mb-4">
                    <h3 class="px-3 text-xs font-bold text-indigo-300/70 uppercase tracking-wider mb-2">{{ $title }}</h3>
                    <ul class="space-y-1">
                        @foreach($links as $link)
                            @php $active = request()->routeIs($link['route'] . '*'); @endphp
                            <li>
                                <a href="{{ route($link['route']) }}"
                                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200
                                          {{ $active ? 'bg-indigo-600 text-white shadow-md shadow-indigo-900/50' : 'text-indigo-200 hover:bg-indigo-800/50 hover:text-white' }}">
                                    <svg class="w-4 h-4 flex-shrink-0 {{ $active ? 'text-white' : 'text-indigo-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $link['icon'] }}"/>
                                    </svg>
                                    {{ $link['label'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </nav>

    </aside>

    {{-- ========== MAIN CONTENT ========== --}}
    <div class="flex-1 flex flex-col h-screen overflow-hidden min-w-0">

        {{-- Top Navbar --}}
        <header class="bg-white border-b border-gray-100 px-4 md:px-6 py-4 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center min-w-0">
                {{-- Hamburger (mobile only) --}}
                <button @click="sidebarOpen = !sidebarOpen"
                        class="md:hidden p-2 mr-3 rounded-lg text-gray-600 hover:bg-gray-100 focus:outline-none transition-colors flex-shrink-0">
                    <svg x-show="!sidebarOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="sidebarOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                {{-- Breadcrumb --}}
                @stack('breadcrumbs')
            </div>
            {{-- Header Icons + Avatar Dropdown --}}
            <div class="flex items-center gap-1 border-l border-gray-200 pl-4">

                {{-- Notification bell --}}
                <div class="relative" x-data="{ notifyOpen: false }">
                    <button @click="notifyOpen = !notifyOpen"
                            @click.outside="notifyOpen = false"
                            class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all duration-200 relative"
                            title="Notifications">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        @if(isset($adminUnreadCount) && $adminUnreadCount > 0)
                            <span class="absolute top-1.5 right-1.5 flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                            </span>
                        @endif
                    </button>

                    {{-- Notifications Dropdown --}}
                    <div x-show="notifyOpen"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 top-12 w-80 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden"
                         style="display: none;">
                        
                        {{-- Header --}}
                        <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                            <div class="flex items-center gap-2">
                                <h3 class="text-sm font-semibold text-gray-800">Notifications</h3>
                                @if(isset($adminUnreadCount) && $adminUnreadCount > 0)
                                    <span class="bg-indigo-100 text-indigo-700 text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $adminUnreadCount }} new</span>
                                @endif
                            </div>
                            @if(isset($adminUnreadCount) && $adminUnreadCount > 0)
                                <form action="{{ route('admin.notifications.mark-all-read') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">Mark all read</button>
                                </form>
                            @endif
                        </div>

                        {{-- List --}}
                        <div class="max-h-80 overflow-y-auto">
                            @if(isset($adminRecentNotifications) && $adminRecentNotifications->count() > 0)
                                @foreach($adminRecentNotifications as $notification)
                                    <div class="px-4 py-3 border-b border-gray-50 flex gap-3 hover:bg-gray-50 transition-colors {{ $notification->is_read ? 'bg-white' : 'bg-indigo-50/30' }}">
                                        {{-- Icon --}}
                                        @php
                                            $iconBg = 'bg-gray-100'; $iconColor = 'text-gray-500'; $iconSvg = 'M15 17h5l-1.405-1.405...';
                                            if ($notification->type === 'new_member') {
                                                $iconBg = 'bg-emerald-100'; $iconColor = 'text-emerald-600'; 
                                                $iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />';
                                            } elseif ($notification->type === 'new_review') {
                                                $iconBg = 'bg-amber-100'; $iconColor = 'text-amber-600';
                                                $iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />';
                                            } elseif ($notification->type === 'new_purchase') {
                                                $iconBg = 'bg-blue-100'; $iconColor = 'text-blue-600';
                                                $iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />';
                                            } elseif ($notification->type === 'expiry_warning') {
                                                $iconBg = 'bg-orange-100'; $iconColor = 'text-orange-600';
                                                $iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />';
                                            } else {
                                                $iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />';
                                            }
                                        @endphp
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 {{ $iconBg }} {{ $iconColor }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $iconSvg !!}</svg>
                                        </div>
                                        {{-- Content --}}
                                        <div class="flex-1 min-w-0">
                                            @if(!$notification->is_read)
                                                <form action="{{ route('admin.notifications.mark-as-read', $notification->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-sm text-gray-800 hover:text-indigo-600 text-left w-full line-clamp-2 leading-tight">
                                                        {{ $notification->message }}
                                                    </button>
                                                </form>
                                            @else
                                                <p class="text-sm text-gray-600 line-clamp-2 leading-tight">{{ $notification->message }}</p>
                                            @endif
                                            <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="px-4 py-8 text-center">
                                    <div class="w-10 h-10 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-2 border border-gray-100">
                                        <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                    </div>
                                    <p class="text-sm text-gray-500">No notifications yet</p>
                                </div>
                            @endif
                        </div>

                        {{-- Footer --}}
                        <div class="px-4 py-2 border-t border-gray-100 bg-gray-50/50 text-center">
                            <a href="{{ route('admin.notifications.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">
                                View all notifications
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Separator --}}
                <div class="w-px h-6 bg-gray-200 mx-1"></div>

                <div class="relative" x-data="{ dropdownOpen: false }">
                    <button @click="dropdownOpen = !dropdownOpen"
                            @click.outside="dropdownOpen = false"
                            class="flex items-center gap-2 cursor-pointer focus:outline-none group"
                            :aria-expanded="dropdownOpen.toString()">
                        <span class="hidden sm:block text-sm text-gray-600 font-medium group-hover:text-gray-900 transition-colors">
                            Admin
                        </span>
                        <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-xs font-bold shadow-sm ring-2 ring-transparent group-hover:ring-blue-200 transition-all">
                            {{ strtoupper(substr(auth()->user()->first_name ?? auth()->user()->email ?? 'A', 0, 1)) }}
                        </div>
                        <svg class="w-3.5 h-3.5 text-gray-400 transition-transform duration-200" :class="dropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    {{-- Dropdown Panel --}}
                    <div x-show="dropdownOpen"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 top-12 w-60 bg-white rounded-xl shadow-xl border border-gray-100 z-50"
                         style="display: none;"
                         role="menu">

                        {{-- Header --}}
                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="text-xs text-gray-500 font-medium">Signed in as</p>
                            <p class="text-sm font-semibold text-gray-800 truncate">{{ auth()->user()->display_name }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
                        </div>

                        {{-- My Profile --}}
                        <div class="py-1">
                            <a href="{{ route('admin.profile.edit') }}"
                               class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors"
                               role="menuitem">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0M19 21a7 7 0 10-14 0"/>
                                </svg>
                                My Profile
                            </a>
                        </div>

                        <div class="border-t border-gray-100"></div>

                        {{-- Settings --}}
                        <div class="py-1">
                            <a href="{{ route('admin.settings.index') }}"
                               class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors"
                               role="menuitem">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0"/>
                                </svg>
                                Settings
                            </a>
                        </div>

                        <div class="border-t border-gray-100"></div>

                        {{-- Logout --}}
                        <div class="py-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors"
                                        role="menuitem">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{-- Content --}}
        <main class="flex-1 overflow-y-auto p-4 md:p-6 bg-gray-50">

            {{-- Flash Messages --}}
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

    @stack('scripts')
</body>
</html>
