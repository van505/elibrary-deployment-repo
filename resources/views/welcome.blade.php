<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ELibrary — Discover Your Next Great Read</title>
    <meta name="description"
        content="Access thousands of ebooks with a single subscription. Choose from Free, Basic, or Premium plans.">

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] }
                }
            }
        }
    </script>

    {{-- Vite compiled CSS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        html {
            scroll-behavior: smooth;
        }

        .navbar-blur {
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            background: rgba(255, 255, 255, 0.98);
        }

        .gradient-text {
            background: linear-gradient(135deg, #1e40af, #2563eb);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .book-card {
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .book-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 36px rgba(0, 0, 0, 0.10);
        }

        @keyframes floatLeft {

            0%,
            100% {
                transform: translateY(0) rotate(-4deg);
            }

            50% {
                transform: translateY(-14px) rotate(-4deg);
            }
        }

        @keyframes floatRight {

            0%,
            100% {
                transform: translateY(0) rotate(4deg);
            }

            50% {
                transform: translateY(-14px) rotate(4deg);
            }
        }

        .float-left {
            animation: floatLeft 4s ease-in-out infinite;
        }

        .float-right {
            animation: floatRight 4.5s ease-in-out infinite;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(24px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeInUp 0.65s ease forwards;
        }

        .fade-in-delay-1 {
            animation: fadeInUp 0.65s 0.15s ease both;
        }

        .fade-in-delay-2 {
            animation: fadeInUp 0.65s 0.30s ease both;
        }

        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: #2563eb;
            border-radius: 3px;
        }

        .badge-popular {
            background-color: #E99E8F;
            color: #7c2d12;
        }

        .book-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }
    </style>
</head>

<body class="bg-white text-gray-900 antialiased">

    {{-- ============================================================
    NAVBAR
    ============================================================ --}}
    <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 bg-slate-900 border-b border-slate-800"
        style="background-image: linear-gradient(to bottom right, rgba(30, 64, 175, 0.85), rgba(15, 23, 42, 0.95))">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                {{-- Logo --}}
                <a href="/" class="flex items-center gap-2 shrink-0">
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <span class="font-bold text-xl text-white tracking-tight">ELibrary</span>
                </a>

                {{-- Center Search --}}
                <div class="hidden md:flex flex-1 max-w-sm mx-8">
                    <div class="relative w-full">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0" />
                            </svg>
                        </span>
                        <input type="text" id="main-header-search" placeholder="Search books, authors..."
                            class="w-full bg-white border border-gray-200 rounded-full pl-9 pr-4 py-2 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-500 transition-colors shadow-sm">
                    </div>
                </div>

                {{-- Desktop Right --}}
                <div class="hidden md:flex items-center gap-6">
                    <a href="#books"
                        class="text-slate-300 hover:text-white text-sm font-medium transition-colors">Books</a>
                    <a href="#pricing"
                        class="text-slate-300 hover:text-white text-sm font-medium transition-colors">Pricing</a>
                    <div class="h-4 w-px bg-slate-700"></div>
                    <a href="{{ route('login') }}"
                        class="text-slate-300 hover:text-white text-sm font-medium transition-colors">Sign In</a>
                    <a href="{{ route('register') }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors">Get
                        Started</a>
                </div>

                {{-- Mobile Hamburger --}}
                <button id="mobile-menu-btn" class="md:hidden p-2 text-slate-300 hover:text-white"
                    aria-label="Open menu">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            {{-- Mobile menu --}}
            <div id="mobile-menu" class="hidden md:hidden pb-4 border-t border-slate-800 mt-0 bg-slate-900"
                style="background-image: linear-gradient(to bottom right, rgba(30, 64, 175, 0.85), rgba(15, 23, 42, 0.95))">
                <div class="flex flex-col gap-3 pt-3 px-4">
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0" />
                            </svg>
                        </span>
                        <input type="text" id="mobile-header-search" placeholder="Search books, authors..."
                            class="w-full bg-white border border-gray-200 rounded-full pl-9 pr-4 py-2 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-blue-500 shadow-sm">
                    </div>
                    <a href="#books" class="text-slate-300 hover:text-white text-sm py-1 mt-2">Books</a>
                    <a href="#pricing" class="text-slate-300 hover:text-white text-sm py-1">Pricing</a>
                    <a href="{{ route('login') }}" class="text-slate-300 hover:text-white text-sm py-1">Sign In</a>
                    <a href="{{ route('register') }}"
                        class="bg-blue-600 text-white text-sm font-semibold px-4 py-2.5 rounded-lg text-center">Get
                        Started</a>
                </div>
            </div>
        </div>
    </nav>

    {{-- ============================================================
    HERO
    ============================================================ --}}
    <section class="bg-white min-h-screen flex items-center pt-16 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 w-full">
            <div class="relative grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-8 items-center">

                {{-- Left Text --}}
                <div class="relative text-left max-w-2xl px-4 sm:px-0 fade-in">
                    <span
                        class="inline-flex items-center gap-2 bg-blue-50 text-blue-700 border border-blue-100 px-3 py-1.5 rounded-full text-xs font-semibold mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-yellow-500" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                        </svg>
                        #1 Digital Library Platform
                    </span>

                    <h1 class="text-4xl sm:text-5xl lg:text-5xl xl:text-6xl font-bold text-gray-900 leading-tight mb-5">
                        Discover Your<br>
                        <span class="gradient-text">Next Great Read</span>
                    </h1>

                    <p class="text-lg text-gray-500 mb-8 leading-relaxed max-w-lg">
                        Access thousands of ebooks, audiobooks, and magazines. Start your journey today in our flexible
                        subscription plans.
                    </p>

                    <div class="flex flex-col sm:flex-row items-center sm:justify-start gap-4 mb-6">
                        <a href="{{ route('register') }}"
                            class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-semibold px-7 py-3.5 rounded-xl transition-all flex items-center justify-center gap-2 shadow-lg shadow-blue-200 hover:-translate-y-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            Start Free Trial
                        </a>
                        <a href="#books"
                            class="w-full sm:w-auto border-2 border-blue-600 text-blue-600 hover:bg-blue-50 font-semibold px-7 py-3.5 rounded-xl transition-all flex items-center justify-center gap-2 hover:-translate-y-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            Explore Books
                        </a>
                    </div>

                    <div class="flex items-center sm:justify-start justify-center gap-6 text-sm text-gray-500">
                        <span class="flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-green-500 flex-shrink-0"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            No credit card required
                        </span>
                        <span class="flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-green-500 flex-shrink-0"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Cancel anytime
                        </span>
                    </div>
                </div>

                {{-- Right Visual Asset (Stacked Books) --}}
                <div class="relative hidden lg:block fade-in-delay-1 w-full h-[600px]">
                    {{-- Soft background glow --}}
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none"
                        aria-hidden="true">
                        <div
                            class="absolute w-[450px] h-[450px] bg-blue-200 opacity-40 rounded-full blur-3xl -translate-x-10 -translate-y-5">
                        </div>
                        <div
                            class="absolute w-[350px] h-[350px] bg-indigo-200 opacity-30 rounded-full blur-2xl translate-x-16 translate-y-16">
                        </div>
                    </div>

                    {{-- Books Container --}}
                    <div class="relative w-full h-full flex items-center justify-center flex-col perspective-[1000px]">
                        {{-- Book 1: Left Background --}}
                        <div class="absolute w-56 aspect-[3/4] rounded-xl shadow-2xl overflow-hidden z-10 transform transition-transform duration-500 hover:-rotate-12 -rotate-[8deg] -translate-x-[110px] translate-y-[30px]"
                            style="box-shadow: -15px 15px 35px rgba(0,0,0,0.2);">
                            @if(isset($heroBooks[1]) && $heroBooks[1]->cover_image)
                                <img src="{{ asset('storage/' . $heroBooks[1]->cover_image) }}" alt="Book 1"
                                    class="w-full h-full object-cover relative z-0">
                            @else
                                <div
                                    class="w-full h-full bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center relative z-0">
                                    <span
                                        class="text-blue-300 font-bold text-center px-4">{{ $heroBooks[1]->title ?? 'Book 1' }}</span>
                                </div>
                            @endif
                            <div class="absolute inset-0 border border-white/20 rounded-xl z-20"></div>
                        </div>

                        {{-- Book 2: Right Background --}}
                        <div class="absolute w-56 aspect-[3/4] rounded-xl shadow-2xl overflow-hidden z-10 transform transition-transform duration-500 hover:rotate-12 rotate-[10deg] translate-x-[120px] translate-y-[45px]"
                            style="box-shadow: 15px 15px 35px rgba(0,0,0,0.2);">
                            @if(isset($heroBooks[2]) && $heroBooks[2]->cover_image)
                                <img src="{{ asset('storage/' . $heroBooks[2]->cover_image) }}" alt="Book 2"
                                    class="w-full h-full object-cover relative z-0">
                            @else
                                <div
                                    class="w-full h-full bg-gradient-to-br from-sky-100 to-blue-200 flex items-center justify-center relative z-0">
                                    <span
                                        class="text-blue-400 font-bold text-center px-4">{{ $heroBooks[2]->title ?? 'Book 2' }}</span>
                                </div>
                            @endif
                            <div class="absolute inset-0 border border-white/20 rounded-xl z-20"></div>
                        </div>

                        {{-- Book 3: Foreground Main --}}
                        <div
                            class="absolute w-64 aspect-[3/4] rounded-xl shadow-[0_30px_60px_-15px_rgba(0,0,0,0.35)] overflow-hidden z-20 transform transition-transform duration-500 hover:-translate-y-3 -translate-y-4">
                            @if(isset($heroBooks[0]) && $heroBooks[0]->cover_image)
                                <img src="{{ asset('storage/' . $heroBooks[0]->cover_image) }}" alt="Book 3"
                                    class="w-full h-full object-cover relative z-0">
                            @else
                                <div
                                    class="w-full h-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center relative z-0">
                                    <span
                                        class="text-white font-bold text-center px-4 text-xl">{{ $heroBooks[0]->title ?? 'Featured Book' }}</span>
                                </div>
                            @endif
                            <div
                                class="absolute inset-x-0 top-0 h-full bg-gradient-to-t from-black/20 to-transparent z-10">
                            </div>
                            <div class="absolute inset-0 border border-white/30 rounded-xl z-20"></div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>

    {{-- ============================================================
    METRICS BAR
    ============================================================ --}}
    <section class="bg-gray-50 py-12 border-y border-gray-100">
        <div class="max-w-5xl mx-auto px-4 sm:px-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach([['10K+', 'Ebooks Available'], ['500+', 'Authors'], ['50+', 'Categories'], ['24/7', 'Access']] as $m)
                    <div class="bg-white rounded-2xl p-6 text-center shadow-sm border border-gray-100 book-card">
                        <div class="text-3xl font-bold text-gray-900 mb-1">{{ $m[0] }}</div>
                        <div class="text-sm text-gray-500 font-medium">{{ $m[1] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================================================
    COMBINED: CATEGORIES + FEATURED BOOKS (with filter pills)
    ============================================================ --}}
    <section id="books" class="bg-gray-50 py-16 border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Section header --}}
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-1">Featured Books</h2>
                    <p class="text-gray-500 text-sm">Browse by category or search to find your next read</p>
                </div>
                {{-- Search bar --}}
                <div class="relative sm:w-72">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0" />
                        </svg>
                    </span>
                    <input id="book-search" type="text" placeholder="Search books, authors..."
                        class="w-full bg-white border border-gray-200 rounded-full pl-9 pr-4 py-2.5 text-sm focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 transition-all shadow-sm">
                </div>
            </div>

            {{-- Category filter pills --}}
            <div id="category-filters" class="flex flex-wrap gap-2 mb-4">
                {{-- "All" pill --}}
                <button data-category="all"
                    class="filter-pill active-pill px-4 py-1.5 rounded-full text-sm font-semibold border-2 border-gray-900 bg-gray-900 text-white transition-all">
                    All Books
                </button>
                {{-- DB categories --}}
                @foreach($categories as $cat)
                    <button data-category="{{ strtolower($cat->name) }}"
                        class="filter-pill px-4 py-1.5 rounded-full text-sm font-medium border-2 border-gray-200 bg-white text-gray-600 hover:border-blue-300 hover:text-blue-600 transition-all">
                        {{ $cat->name }}
                    </button>
                @endforeach
            </div>

            {{-- Access filter pills --}}
            <div id="access-filters" class="flex flex-wrap items-center gap-2 mb-8">
                <span class="text-sm font-medium text-gray-500 mr-1">Access Level:</span>
                <button data-access="all"
                    class="access-pill active-pill px-4 py-1.5 rounded-full text-sm font-semibold border-2 border-blue-600 bg-blue-50 text-blue-700 transition-all">
                    All
                </button>
                <button data-access="free"
                    class="access-pill px-4 py-1.5 rounded-full text-sm font-medium border-2 border-gray-200 bg-white text-gray-600 hover:border-green-300 hover:text-green-600 transition-all">
                    Free
                </button>
                <button data-access="basic"
                    class="access-pill px-4 py-1.5 rounded-full text-sm font-medium border-2 border-gray-200 bg-white text-gray-600 hover:border-blue-300 hover:text-blue-600 transition-all">
                    Basic
                </button>
                <button data-access="premium"
                    class="access-pill px-4 py-1.5 rounded-full text-sm font-medium border-2 border-gray-200 bg-white text-gray-600 hover:border-purple-300 hover:text-purple-600 transition-all">
                    Premium
                </button>
            </div>

            {{-- Book grid --}}
            <div id="books-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5">
                @forelse($featuredBooks as $book)
                    @php
                        $badgeColor = match ($book->access_level ?? 'free') {
                            'free' => 'bg-green-100 text-green-700',
                            'basic' => 'bg-blue-100 text-blue-700',
                            'premium' => 'bg-purple-100 text-purple-700',
                            default => 'bg-gray-100 text-gray-600',
                        };
                        $badgeText = ucfirst($book->access_level ?? 'free');
                        $authorName = $book->authors->pluck('full_name')->filter()->join(', ')
                            ?: $book->authors->pluck('name')->filter()->join(', ')
                            ?: 'Unknown Author';
                        $categorySlug = $book->category ? strtolower($book->category->name) : '';
                    @endphp
                    <div class="book-item book-card group cursor-pointer bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm"
                        data-category="{{ $categorySlug }}" data-access="{{ strtolower($book->access_level ?? 'free') }}" data-title="{{ strtolower($book->title) }}"
                        data-author="{{ strtolower($authorName) }}" onclick="window.location='{{ route('login') }}'">
                        <div class="relative w-full overflow-hidden bg-gray-100" style="aspect-ratio:3/4;">
                            @if($book->cover_image)
                                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" loading="lazy"
                                    class="book-img group-hover:scale-105 transition-transform duration-500"
                                    onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=300&h=400&fit=crop';">
                            @else
                                <div
                                    class="w-full h-full bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-14 h-14 text-blue-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                </div>
                            @endif
                            <span
                                class="absolute top-2.5 left-2.5 px-2 py-0.5 rounded-md text-[11px] font-bold {{ $badgeColor }}">
                                {{ $badgeText }}
                            </span>
                        </div>
                        <div class="p-3">
                            <h3 class="font-semibold text-gray-900 text-sm truncate">{{ $book->title }}</h3>
                            <p class="text-gray-500 text-xs mt-0.5 truncate">{{ $authorName }}</p>
                        </div>
                    </div>
                @empty
                    {{-- Static fallback when DB is empty --}}
                    @php
                        $staticBooks = [
                            ['title' => 'Pride and Prejudice', 'author' => 'Jane Austen', 'badge' => 'Basic', 'cls' => 'bg-blue-100 text-blue-700', 'img' => 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=400&q=80', 'cat' => 'classic'],
                            ['title' => 'Alice in Wonderland', 'author' => 'Lewis Carroll', 'badge' => 'Free', 'cls' => 'bg-green-100 text-green-700', 'img' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?w=400&q=80', 'cat' => 'fantasy'],
                            ['title' => 'Romeo and Juliet', 'author' => 'W. Shakespeare', 'badge' => 'Basic', 'cls' => 'bg-blue-100 text-blue-700', 'img' => 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=400&q=80', 'cat' => 'romance'],
                            ['title' => 'Moby Dick', 'author' => 'Herman Melville', 'badge' => 'Premium', 'cls' => 'bg-purple-100 text-purple-700', 'img' => 'https://images.unsplash.com/photo-1495640388908-05fa85288e61?w=400&q=80', 'cat' => 'action'],
                        ];
                    @endphp
                    @foreach($staticBooks as $sb)
                        <div class="book-item book-card group cursor-pointer bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm"
                            data-category="{{ $sb['cat'] }}" data-title="{{ strtolower($sb['title']) }}"
                            data-author="{{ strtolower($sb['author']) }}" onclick="window.location='{{ route('login') }}'">
                            <div class="relative w-full overflow-hidden bg-gray-100" style="aspect-ratio:3/4;">
                                <img src="{{ $sb['img'] }}" alt="{{ $sb['title'] }}" loading="lazy"
                                    class="book-img group-hover:scale-105 transition-transform duration-500"
                                    onerror="this.style.display='none'; this.parentElement.style.background='#dbeafe';">
                                <span
                                    class="absolute top-2.5 left-2.5 px-2 py-0.5 rounded-md text-[11px] font-bold {{ $sb['cls'] }}">
                                    {{ $sb['badge'] }}
                                </span>
                            </div>
                            <div class="p-3">
                                <h3 class="font-semibold text-gray-900 text-sm truncate">{{ $sb['title'] }}</h3>
                                <p class="text-gray-500 text-xs mt-0.5 truncate">{{ $sb['author'] }}</p>
                            </div>
                        </div>
                    @endforeach
                @endforelse
            </div>

            {{-- Empty state (shown by JS when no results match) --}}
            <div id="no-results" class="hidden text-center py-16">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0" />
                </svg>
                <p class="text-gray-400 font-medium">No books found.</p>
                <p class="text-gray-400 text-sm mt-1">Try a different category or search term.</p>
            </div>

            <div class="text-center mt-8">
                <a href="{{ route('login') }}"
                    class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-medium text-sm">
                    Sign in to see all books
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>
    </section>

    {{-- ============================================================
    HOW IT WORKS
    ============================================================ --}}
    <section id="how-it-works" class="bg-gray-50 py-20 border-t border-gray-100">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">How It Works</h2>
                <p class="text-gray-500">Get started in 3 simple steps</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- Step 1 --}}
                <div class="bg-white rounded-2xl p-8 text-center border border-gray-100 shadow-sm book-card relative">
                    <div
                        class="absolute -top-4 left-1/2 -translate-x-1/2 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-sm shadow">
                        1</div>
                    <div
                        class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-5 mt-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-blue-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-900 text-lg mb-2">Create Account</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Sign up for free and unlock access to our digital
                        librection.</p>
                </div>

                {{-- Step 2 --}}
                <div class="bg-white rounded-2xl p-8 text-center border border-gray-100 shadow-sm book-card relative">
                    <div
                        class="absolute -top-4 left-1/2 -translate-x-1/2 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-sm shadow">
                        2</div>
                    <div
                        class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-5 mt-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-blue-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-900 text-lg mb-2">Choose Plan</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Select from Free, Basic, or Premium plans based on
                        your reading needs.</p>
                </div>

                {{-- Step 3 --}}
                <div class="bg-white rounded-2xl p-8 text-center border border-gray-100 shadow-sm book-card relative">
                    <div
                        class="absolute -top-4 left-1/2 -translate-x-1/2 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-sm shadow">
                        3</div>
                    <div
                        class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-5 mt-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-blue-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-900 text-lg mb-2">Start Reading</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Browse thousands of books and start reading
                        instantly on any device.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
    PRICING
    ============================================================ --}}
    <section id="pricing" class="bg-white py-20">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Simple, Transparent Pricing</h2>
                <p class="text-gray-500">Choose the plan that works for you</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">

                {{-- FREE --}}
                <div class="bg-white rounded-2xl p-7 border-2 border-gray-100 shadow-sm book-card flex flex-col">
                    <h3 class="text-xl font-bold text-gray-900 mb-1">Free</h3>
                    <p class="text-gray-500 text-sm mb-5">Perfect to get started</p>
                    <div class="mb-6">
                        <span class="text-4xl font-bold text-gray-900">₱0</span>
                        <span class="text-gray-400 text-sm">/month</span>
                    </div>
                    <ul class="space-y-3 mb-7 flex-1">
                        <li class="flex items-center gap-2.5 text-sm text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-green-500 flex-shrink-0"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Free ebooks
                        </li>
                        <li class="flex items-center gap-2.5 text-sm text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-green-500 flex-shrink-0"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Standard access
                        </li>
                    </ul>
                    <a href="{{ route('register') }}"
                        class="block text-center border-2 border-blue-600 text-blue-600 hover:bg-blue-50 font-semibold py-3 rounded-xl transition-colors text-sm">
                        Get Started
                    </a>
                </div>

                {{-- BASIC (highlighted) --}}
                <div
                    class="bg-white rounded-2xl p-7 border-2 border-blue-600 shadow-lg book-card flex flex-col relative">
                    <div class="absolute -top-3.5 left-1/2 -translate-x-1/2">
                        <span
                            class="badge-popular text-xs font-bold px-3.5 py-1 rounded-full whitespace-nowrap">Popular</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-1 mt-2">Basic</h3>
                    <p class="text-gray-500 text-sm mb-5">For regular readers</p>
                    <div class="mb-6">
                        <span class="text-4xl font-bold text-gray-900">₱99</span>
                        <span class="text-gray-400 text-sm">/month</span>
                    </div>
                    <ul class="space-y-3 mb-7 flex-1">
                        <li class="flex items-center gap-2.5 text-sm text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-600 flex-shrink-0"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Basic tier ebooks
                        </li>
                        <li class="flex items-center gap-2.5 text-sm text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-600 flex-shrink-0"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Premium Features
                        </li>
                    </ul>
                    <a href="{{ route('register') }}"
                        class="block text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition-colors text-sm">
                        Choose Basic
                    </a>
                </div>

                {{-- PREMIUM --}}
                <div class="bg-white rounded-2xl p-7 border-2 border-gray-100 shadow-sm book-card flex flex-col">
                    <h3 class="text-xl font-bold text-gray-900 mb-1">Premium</h3>
                    <p class="text-gray-500 text-sm mb-5">For power readers</p>
                    <div class="mb-6">
                        <span class="text-4xl font-bold text-gray-900">₱199</span>
                        <span class="text-gray-400 text-sm">/month</span>
                    </div>
                    <ul class="space-y-3 mb-7 flex-1">
                        <li class="flex items-center gap-2.5 text-sm text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-green-500 flex-shrink-0"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Premium ebooks
                        </li>
                        <li class="flex items-center gap-2.5 text-sm text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-green-500 flex-shrink-0"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            All Features
                        </li>
                    </ul>
                    <a href="{{ route('register') }}"
                        class="block text-center border-2 border-gray-200 text-gray-700 hover:border-blue-400 hover:text-blue-600 font-semibold py-3 rounded-xl transition-colors text-sm">
                        Upgrade
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
    FOOTER
    ============================================================ --}}
    <footer class="bg-slate-900 pt-14 pb-8"
        style="background-image: linear-gradient(to bottom right, rgba(30, 64, 175, 0.85), rgba(15, 23, 42, 0.95))">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-10">

                {{-- Brand --}}
                <div class="md:col-span-2">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <span class="font-bold text-lg text-white tracking-tight">ELibrary</span>
                    </div>
                    <p class="text-sm text-slate-400 leading-relaxed max-w-xs">
                        Access thousands of ebooks from anywhere, anytime. Your digital library, always open.
                    </p>
                </div>

                {{-- Quick Links --}}
                <div>
                    <h4 class="font-bold text-white text-sm mb-4">Quick Links</h4>
                    <ul class="space-y-2.5 text-sm text-slate-400">
                        <li><a href="#books" class="hover:text-blue-400 transition-colors">Browse Books</a></li>
                        <li><a href="#pricing" class="hover:text-blue-400 transition-colors">Pricing</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-blue-400 transition-colors">Login</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-blue-400 transition-colors">Register</a>
                        </li>
                    </ul>
                </div>

                {{-- Support --}}
                <div>
                    <h4 class="font-bold text-white text-sm mb-4">Support</h4>
                    <ul class="space-y-2.5 text-sm text-slate-400">
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Help Center</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Contact Us</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Terms of Service</a></li>
                    </ul>
                </div>
            </div>

            {{-- Bottom bar --}}
            <div class="border-t border-slate-700 pt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-xs text-slate-500">© {{ date('Y') }} ELibrary. All rights reserved.</p>
                <div class="flex items-center gap-3">
                    {{-- Facebook --}}
                    <a href="#"
                        class="w-8 h-8 bg-slate-800 rounded-lg flex items-center justify-center text-slate-400 hover:text-blue-400 hover:bg-slate-700 transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                        </svg>
                    </a>
                    {{-- Instagram --}}
                    <a href="#"
                        class="w-8 h-8 bg-slate-800 rounded-lg flex items-center justify-center text-slate-400 hover:text-pink-400 hover:bg-slate-700 transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" />
                        </svg>
                    </a>
                    {{-- LinkedIn --}}
                    <a href="#"
                        class="w-8 h-8 bg-slate-800 rounded-lg flex items-center justify-center text-slate-400 hover:text-blue-400 hover:bg-slate-700 transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                        </svg>
                    </a>
                    {{-- YouTube --}}
                    <a href="#"
                        class="w-8 h-8 bg-slate-800 rounded-lg flex items-center justify-center text-slate-400 hover:text-red-400 hover:bg-slate-700 transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M23.495 6.205a3.007 3.007 0 00-2.088-2.088c-1.87-.501-9.396-.501-9.396-.501s-7.507-.01-9.396.501A3.007 3.007 0 00.527 6.205a31.247 31.247 0 00-.522 5.805 31.247 31.247 0 00.522 5.783 3.007 3.007 0 002.088 2.088c1.868.502 9.396.502 9.396.502s7.506 0 9.396-.502a3.007 3.007 0 002.088-2.088 31.247 31.247 0 00.5-5.783 31.247 31.247 0 00-.5-5.805zM9.609 15.601V8.408l6.264 3.602z" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    {{-- JAVASCRIPT --}}
    <script>
        // ── Mobile menu ──────────────────────────────────────────────────────────
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        btn?.addEventListener('click', () => menu.classList.toggle('hidden'));
        menu?.querySelectorAll('a').forEach(a => a.addEventListener('click', () => menu.classList.add('hidden')));

        // ── Navbar scroll shadow ─────────────────────────────────────────────────
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => navbar.classList.toggle('shadow-md', window.scrollY > 10));

        // ── Book filter (category pills + search) ────────────────────────────────
        const pills = document.querySelectorAll('.filter-pill');
        const accessPills = document.querySelectorAll('.access-pill');
        const books = document.querySelectorAll('.book-item');
        const searchEl = document.getElementById('book-search');
        const headerSearchDesktop = document.getElementById('main-header-search');
        const headerSearchMobile = document.getElementById('mobile-header-search');
        const noResults = document.getElementById('no-results');

        let activeCategory = 'all';
        let activeAccess = 'all';
        let searchTerm = '';

        function applyFilters() {
            let visible = 0;
            books.forEach(book => {
                const cat = book.dataset.category || '';
                const access = book.dataset.access || '';
                const title = book.dataset.title || '';
                const author = book.dataset.author || '';

                const catMatch = activeCategory === 'all' || cat === activeCategory;
                const accessMatch = activeAccess === 'all' || access === activeAccess;
                const searchMatch = searchTerm === '' || title.includes(searchTerm) || author.includes(searchTerm);

                if (catMatch && accessMatch && searchMatch) {
                    book.classList.remove('hidden');
                    visible++;
                } else {
                    book.classList.add('hidden');
                }
            });

            noResults?.classList.toggle('hidden', visible > 0);
        }

        // Pill click
        pills.forEach(pill => {
            pill.addEventListener('click', () => {
                activeCategory = pill.dataset.category;

                // Update pill styles
                pills.forEach(p => {
                    p.classList.remove('active-pill', 'bg-gray-900', 'text-white', 'border-gray-900');
                    p.classList.add('bg-white', 'text-gray-600', 'border-gray-200');
                });
                pill.classList.add('active-pill', 'bg-gray-900', 'text-white', 'border-gray-900');
                pill.classList.remove('bg-white', 'text-gray-600', 'border-gray-200');

                applyFilters();
            });
        });

        // Access Pill click
        accessPills.forEach(pill => {
            pill.addEventListener('click', () => {
                activeAccess = pill.dataset.access;

                // Update pill styles
                accessPills.forEach(p => {
                    p.classList.remove('active-pill', 'bg-blue-50', 'text-blue-700', 'border-blue-600');
                    p.classList.add('bg-white', 'text-gray-600', 'border-gray-200');
                });
                pill.classList.add('active-pill', 'bg-blue-50', 'text-blue-700', 'border-blue-600');
                pill.classList.remove('bg-white', 'text-gray-600', 'border-gray-200');

                applyFilters();
            });
        });

        // Search Sync Handler
        function handleSearch(e) {
            searchTerm = e.target.value.toLowerCase().trim();
            if (searchEl && searchEl !== e.target) searchEl.value = e.target.value;
            if (headerSearchDesktop && headerSearchDesktop !== e.target) headerSearchDesktop.value = e.target.value;
            if (headerSearchMobile && headerSearchMobile !== e.target) headerSearchMobile.value = e.target.value;
            
            applyFilters();
            
            // Scroll to books section on typing if from header
            if (e.target.id === 'main-header-search' || e.target.id === 'mobile-header-search') {
                if (searchTerm.length > 0) {
                    const booksSection = document.getElementById('books');
                    if (booksSection) booksSection.scrollIntoView({ behavior: 'smooth' });
                }
            }
        }

        searchEl?.addEventListener('input', handleSearch);
        headerSearchDesktop?.addEventListener('input', handleSearch);
        headerSearchMobile?.addEventListener('input', handleSearch);
    </script>

</body>

</html>