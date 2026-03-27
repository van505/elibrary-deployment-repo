<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ELibrary — @yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden">

    {{-- ========== SIDEBAR ========== --}}
    <aside class="w-64 bg-slate-800 flex flex-col flex-shrink-0 h-screen overflow-y-auto">

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
        <nav class="flex-1 px-3 py-4 space-y-1">
            @php
            $links = [
                ['route' => 'member.dashboard',          'label' => 'Dashboard',            'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                ['route' => 'member.ebooks.index',       'label' => 'Browse Ebooks',        'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                ['route' => 'member.subscriptions.index','label' => 'My Subscription',      'icon' => 'M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z'],
                ['route' => 'member.my-ebooks',          'label' => 'My Reading History',   'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['route' => 'member.reviews.index',      'label' => 'My Reviews',           'icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z'],
                ['route' => 'member.profile.edit',       'label' => 'My Profile',           'icon' => 'M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0M19 21a7 7 0 10-14 0'],
            ];
            @endphp

            @foreach($links as $link)
                @php $active = request()->routeIs($link['route'] . '*'); @endphp
                <a href="{{ route($link['route']) }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                          {{ $active ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $link['icon'] }}"/>
                    </svg>
                    {{ $link['label'] }}
                </a>
            @endforeach
        </nav>

        {{-- User + Logout --}}
        <div class="px-4 py-4 border-t border-slate-700">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-xs font-bold">
                    {{ strtoupper(substr(auth()->user()->member->full_name ?: auth()->user()->email ?? 'M', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white text-sm font-medium truncate">{{ auth()->user()->member->full_name ?: (auth()->user()->email ?? '') }}</p>
                    <p class="text-slate-400 text-xs truncate">Member</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-slate-300 hover:bg-slate-700 hover:text-white rounded-lg text-sm transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- ========== MAIN CONTENT ========== --}}
    <div class="flex-1 flex flex-col h-screen overflow-hidden">
        <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between flex-shrink-0">
            <h1 class="text-xl font-semibold text-gray-800">@yield('title', 'Dashboard')</h1>
            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-600">{{ auth()->user()->member->full_name ?: (auth()->user()->email ?? '') }}</span>
                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-xs font-bold">
                    {{ strtoupper(substr(auth()->user()->member->full_name ?: auth()->user()->email ?? 'M', 0, 1)) }}
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6">
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

</body>
</html>
