<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ELibrary — Discover, Read, Enjoy</title>
    <meta name="description" content="Access thousands of ebooks with a single subscription. Choose from Free, Basic, or Premium plans.">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Playfair Display', serif; }
        .gradient-bg { background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 50%, #7c3aed 100%); }
        .gradient-hero { background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 30%, #312e81 100%); }
        .glass { background: rgba(255,255,255,0.08); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.15); }
        .glass-card { background: rgba(255,255,255,0.95); backdrop-filter: blur(10px); }
        @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-10px)} }
        .float { animation: float 4s ease-in-out infinite; }
        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }
        .shimmer { 
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            background-size: 1000px 100%;
            animation: shimmer 3s infinite linear;
        }
        .book-shadow { box-shadow: 0 10px 40px -10px rgba(0,0,0,0.2), 0 4px 12px rgba(0,0,0,0.1); }
        .book-hover:hover { transform: translateY(-8px) scale(1.02); }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .category-pill { transition: all 0.3s ease; }
        .category-pill:hover { transform: translateY(-2px); box-shadow: 0 8px 25px -5px rgba(37, 99, 235, 0.3); }
        .search-glow:focus { box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15); }
    </style>
</head>
<body class="bg-gray-50">

    {{-- Navbar --}}
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-md border-b border-gray-200/80 px-4 sm:px-6 py-3">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <a href="/" class="flex items-center gap-2.5 group">
                <div class="w-9 h-9 bg-gradient-to-br from-blue-600 to-purple-600 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow">
                    <i class="fas fa-book-open text-white text-sm"></i>
                </div>
                <span class="font-bold text-gray-900 text-xl tracking-tight">ELibrary</span>
            </a>
            
            <div class="hidden md:flex items-center gap-1 bg-gray-100/80 rounded-full px-2 py-1.5">
                <a href="#books" class="px-4 py-1.5 text-sm font-medium text-gray-600 hover:text-gray-900 rounded-full hover:bg-white transition-all">Books</a>
                <a href="#categories" class="px-4 py-1.5 text-sm font-medium text-gray-600 hover:text-gray-900 rounded-full hover:bg-white transition-all">Categories</a>
                <a href="#pricing" class="px-4 py-1.5 text-sm font-medium text-gray-600 hover:text-gray-900 rounded-full hover:bg-white transition-all">Pricing</a>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="hidden sm:block text-gray-600 hover:text-gray-900 text-sm font-medium transition-colors px-3 py-2">Sign In</a>
                <a href="{{ route('register') }}" class="bg-gray-900 hover:bg-gray-800 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-all shadow-md hover:shadow-lg">
                    Get Started
                </a>
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <section class="gradient-hero min-h-[85vh] flex items-center justify-center px-4 sm:px-6 pt-24 pb-16 relative overflow-hidden">
        {{-- Background Effects --}}
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-blue-500/20 rounded-full blur-3xl"></div>
            <div class="absolute top-1/2 -left-20 w-72 h-72 bg-purple-500/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-1/4 w-80 h-80 bg-indigo-500/10 rounded-full blur-3xl"></div>
        </div>
        
        <div class="max-w-7xl mx-auto w-full relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                {{-- Left Content --}}
                <div class="text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 glass px-4 py-2 rounded-full text-blue-200 text-xs font-semibold mb-6">
                        <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                        Digital Library Platform
                    </div>
                    
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-display font-bold text-white leading-tight mb-6">
                        Discover Your Next
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-300 to-purple-300">Great Read</span>
                    </h1>
                    
                    <p class="text-lg text-gray-300 mb-8 max-w-xl mx-auto lg:mx-0 leading-relaxed">
                        Access thousands of ebooks, audiobooks, and magazines. Start your reading journey today with our flexible subscription plans.
                    </p>
                    
                    <div class="flex flex-wrap gap-3 justify-center lg:justify-start">
                        <a href="{{ route('register') }}" class="bg-white text-gray-900 font-semibold px-8 py-3.5 rounded-xl hover:bg-gray-100 transition-all shadow-xl text-sm inline-flex items-center gap-2">
                            <i class="fas fa-rocket text-blue-600"></i>
                            Start Free Trial
                        </a>
                        <a href="#books" class="glass text-white font-medium px-8 py-3.5 rounded-xl hover:bg-white/10 transition-all text-sm inline-flex items-center gap-2">
                            <i class="fas fa-compass"></i>
                            Explore Books
                        </a>
                    </div>
                    
                    <div class="flex items-center gap-6 mt-10 justify-center lg:justify-start text-sm text-gray-400">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-check-circle text-green-400"></i>
                            <span>No credit card required</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-check-circle text-green-400"></i>
                            <span>Cancel anytime</span>
                        </div>
                    </div>
                </div>
                
                {{-- Right Content - Featured Books --}}
                <div class="hidden lg:block relative">
                    <div class="relative w-full h-[500px]">
                        @forelse($featuredEbooks->take(4) as $index => $ebook)
                        @php
                            $positions = [
                                ['top' => '5%', 'left' => '10%', 'rotate' => '-8deg', 'z' => 30, 'delay' => '0s'],
                                ['top' => '15%', 'right' => '5%', 'rotate' => '6deg', 'z' => 20, 'delay' => '0.5s'],
                                ['bottom' => '20%', 'left' => '5%', 'rotate' => '5deg', 'z' => 25, 'delay' => '1s'],
                                ['bottom' => '10%', 'right' => '15%', 'rotate' => '-3deg', 'z' => 35, 'delay' => '1.5s'],
                            ];
                            $pos = $positions[$index % 4];
                        @endphp
                        <div class="absolute w-36 h-52 rounded-lg overflow-hidden book-shadow float" 
                             style="top: {{ $pos['top'] ?? 'auto' }}; bottom: {{ $pos['bottom'] ?? 'auto' }}; left: {{ $pos['left'] ?? 'auto' }}; right: {{ $pos['right'] ?? 'auto' }}; transform: rotate({{ $pos['rotate'] }}); z-index: {{ $pos['z'] }}; animation-delay: {{ $pos['delay'] }};">
                            @if($ebook->cover_image)
                                <img src="{{ asset('storage/' . $ebook->cover_image) }}" alt="{{ $ebook->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center p-4 text-center">
                                    <span class="text-white text-xs font-medium line-clamp-2">{{ $ebook->title }}</span>
                                </div>
                            @endif
                        </div>
                        @empty
                        {{-- Default decorative books --}}
                        <div class="absolute top-[5%] left-[10%] w-36 h-52 bg-gradient-to-br from-amber-400 to-orange-500 rounded-lg book-shadow float" style="transform: rotate(-8deg); z-index: 30;"></div>
                        <div class="absolute top-[15%] right-[5%] w-36 h-52 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-lg book-shadow float" style="transform: rotate(6deg); z-index: 20; animation-delay: 0.5s;"></div>
                        <div class="absolute bottom-[20%] left-[5%] w-36 h-52 bg-gradient-to-br from-rose-400 to-pink-500 rounded-lg book-shadow float" style="transform: rotate(5deg); z-index: 25; animation-delay: 1s;"></div>
                        <div class="absolute bottom-[10%] right-[15%] w-36 h-52 bg-gradient-to-br from-violet-400 to-purple-500 rounded-lg book-shadow float" style="transform: rotate(-3deg); z-index: 35; animation-delay: 1.5s;"></div>
                        @endforelse
                        
                        {{-- Center glow --}}
                        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-blue-500/30 rounded-full blur-3xl"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Stats Section --}}
    <section class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                <div class="p-4">
                    <div class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1">10K+</div>
                    <div class="text-sm text-gray-500">Ebooks Available</div>
                </div>
                <div class="p-4">
                    <div class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1">500+</div>
                    <div class="text-sm text-gray-500">Authors</div>
                </div>
                <div class="p-4">
                    <div class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1">50+</div>
                    <div class="text-sm text-gray-500">Categories</div>
                </div>
                <div class="p-4">
                    <div class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1">24/7</div>
                    <div class="text-sm text-gray-500">Access Anytime</div>
                </div>
            </div>
        </div>
    </section>

    {{-- Categories Section --}}
    <section id="categories" class="py-16 px-4 sm:px-6 bg-gray-50">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-display font-bold text-gray-900 mb-3">Browse by Category</h2>
                <p class="text-gray-500 max-w-md mx-auto">Explore our extensive collection across various genres and topics</p>
            </div>
            
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4">
                @forelse($categories as $category)
                <a href="{{ route('login') }}" class="category-pill bg-white rounded-2xl p-5 text-center border border-gray-100 hover:border-blue-200 group">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:from-blue-100 group-hover:to-blue-200 transition-all">
                        <i class="fas fa-folder-open text-blue-600 text-lg"></i>
                    </div>
                    <h3 class="font-semibold text-gray-800 text-sm mb-1">{{ $category->name }}</h3>
                    <p class="text-xs text-gray-400">Explore</p>
                </a>
                @empty
                @php
                $defaultCategories = [
                    ['name' => 'Fiction', 'icon' => 'fa-book', 'color' => 'from-purple-50 to-purple-100', 'iconColor' => 'text-purple-600'],
                    ['name' => 'Technology', 'icon' => 'fa-laptop-code', 'color' => 'from-blue-50 to-blue-100', 'iconColor' => 'text-blue-600'],
                    ['name' => 'Business', 'icon' => 'fa-briefcase', 'color' => 'from-amber-50 to-amber-100', 'iconColor' => 'text-amber-600'],
                    ['name' => 'Science', 'icon' => 'fa-flask', 'color' => 'from-emerald-50 to-emerald-100', 'iconColor' => 'text-emerald-600'],
                    ['name' => 'History', 'icon' => 'fa-landmark', 'color' => 'from-rose-50 to-rose-100', 'iconColor' => 'text-rose-600'],
                    ['name' => 'Arts', 'icon' => 'fa-palette', 'color' => 'from-pink-50 to-pink-100', 'iconColor' => 'text-pink-600'],
                ];
                @endphp
                @foreach($defaultCategories as $cat)
                <a href="{{ route('login') }}" class="category-pill bg-white rounded-2xl p-5 text-center border border-gray-100 hover:border-blue-200 group">
                    <div class="w-12 h-12 bg-gradient-to-br {{ $cat['color'] }} rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-all">
                        <i class="fas {{ $cat['icon'] }} {{ $cat['iconColor'] }} text-lg"></i>
                    </div>
                    <h3 class="font-semibold text-gray-800 text-sm mb-1">{{ $cat['name'] }}</h3>
                    <p class="text-xs text-gray-400">Explore</p>
                </a>
                @endforeach
                @endforelse
            </div>
        </div>
    </section>

    {{-- Books Gallery Section --}}
    <section id="books" class="py-20 px-4 sm:px-6 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-10">
                <div>
                    <h2 class="text-3xl font-display font-bold text-gray-900 mb-2">Featured Books</h2>
                    <p class="text-gray-500">Handpicked selection from our library</p>
                </div>
                <a href="{{ route('login') }}" class="text-blue-600 font-medium text-sm hover:text-blue-700 inline-flex items-center gap-1 group">
                    View All Books
                    <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
            
            {{-- Search Bar --}}
            <div class="max-w-2xl mx-auto mb-12">
                <form action="{{ route('login') }}" method="GET" class="relative">
                    <input type="text" placeholder="Search for books, authors, or genres..." 
                           class="w-full bg-gray-50 border border-gray-200 rounded-2xl pl-12 pr-4 py-4 text-sm search-glow focus:outline-none focus:border-blue-400 transition-all">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 bg-gray-900 text-white px-5 py-2 rounded-xl text-sm font-medium hover:bg-gray-800 transition-colors">
                        Search
                    </button>
                </form>
            </div>

            {{-- Books Grid --}}
            @if($ebooks->isEmpty())
            <div class="text-center py-16 bg-gray-50 rounded-3xl">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-books text-gray-300 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Books Coming Soon</h3>
                <p class="text-gray-500 max-w-md mx-auto">Our library is being curated. Check back soon for amazing content!</p>
            </div>
            @else
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-5">
                @foreach($ebooks as $ebook)
                @php
                    $levelColors = [
                        'free' => 'bg-green-100 text-green-700 border-green-200',
                        'basic' => 'bg-blue-100 text-blue-700 border-blue-200',
                        'premium' => 'bg-purple-100 text-purple-700 border-purple-200'
                    ];
                    $levelColor = $levelColors[$ebook->access_level] ?? 'bg-gray-100 text-gray-600 border-gray-200';
                    $levelIcon = $ebook->access_level === 'free' ? 'fa-gift' : ($ebook->access_level === 'basic' ? 'fa-star' : 'fa-crown');
                @endphp
                <div class="group">
                    {{-- Book Cover --}}
                    <div class="relative aspect-[2/3] rounded-xl overflow-hidden book-shadow book-hover transition-all duration-300 mb-3">
                        @if($ebook->cover_image)
                            <img src="{{ asset('storage/' . $ebook->cover_image) }}" alt="{{ $ebook->title }}" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-blue-100 to-purple-100 flex items-center justify-center p-4">
                                <div class="text-center">
                                    <i class="fas fa-book text-blue-300 text-3xl mb-2"></i>
                                    <p class="text-blue-400 text-xs font-medium line-clamp-2">{{ $ebook->title }}</p>
                                </div>
                            </div>
                        @endif
                        
                        {{-- Access Level Badge --}}
                        <div class="absolute top-2 left-2">
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-semibold border {{ $levelColor }}">
                                <i class="fas {{ $levelIcon }} text-[10px]"></i>
                                {{ ucfirst($ebook->access_level) }}
                            </span>
                        </div>
                        
                        {{-- Hover Overlay --}}
                        <div class="absolute inset-0 bg-gray-900/80 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center gap-3 p-4">
                            <a href="{{ route('login') }}" class="w-full bg-white text-gray-900 font-semibold py-2.5 rounded-lg text-sm text-center hover:bg-gray-100 transition-colors flex items-center justify-center gap-2">
                                <i class="fas fa-book-reader"></i>
                                Read Now
                            </a>
                            <span class="text-white/70 text-xs text-center">Login required to read</span>
                        </div>
                    </div>
                    
                    {{-- Book Info --}}
                    <div>
                        <h3 class="font-semibold text-gray-900 text-sm line-clamp-2 mb-1 group-hover:text-blue-600 transition-colors">
                            {{ $ebook->title }}
                        </h3>
                        <p class="text-xs text-gray-500 truncate">
                            {{ $ebook->authors->pluck('full_name')->join(', ') ?: 'Unknown Author' }}
                        </p>
                        @if($ebook->category)
                        <p class="text-xs text-blue-500 mt-1">{{ $ebook->category->name }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @endif
            
            {{-- CTA to see more --}}
            <div class="text-center mt-12">
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium px-6 py-3 rounded-xl transition-colors">
                    <i class="fas fa-th-large"></i>
                    Browse Full Library
                </a>
            </div>
        </div>
    </section>

    {{-- How It Works --}}
    <section class="py-20 px-4 sm:px-6 bg-gray-50">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-14">
                <h2 class="text-3xl font-display font-bold text-gray-900 mb-3">How It Works</h2>
                <p class="text-gray-500 max-w-md mx-auto">Get started with ELibrary in three simple steps</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white rounded-2xl p-8 border border-gray-100 text-center relative">
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-sm">1</div>
                    <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-5">
                        <i class="fas fa-user-plus text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 text-lg mb-2">Create Account</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Sign up for free and unlock access to our digital library collection.</p>
                </div>
                
                <div class="bg-white rounded-2xl p-8 border border-gray-100 text-center relative">
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-sm">2</div>
                    <div class="w-16 h-16 bg-purple-50 rounded-2xl flex items-center justify-center mx-auto mb-5">
                        <i class="fas fa-crown text-purple-600 text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 text-lg mb-2">Choose Plan</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Select from Free, Basic, or Premium plans based on your reading needs.</p>
                </div>
                
                <div class="bg-white rounded-2xl p-8 border border-gray-100 text-center relative">
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-sm">3</div>
                    <div class="w-16 h-16 bg-green-50 rounded-2xl flex items-center justify-center mx-auto mb-5">
                        <i class="fas fa-book-open text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 text-lg mb-2">Start Reading</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Browse thousands of books and start reading instantly on any device.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Pricing Section --}}
    <section id="pricing" class="py-20 px-4 sm:px-6 bg-white">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-14">
                <h2 class="text-3xl font-display font-bold text-gray-900 mb-3">Simple, Transparent Pricing</h2>
                <p class="text-gray-500">Choose the perfect plan for your reading journey</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-6">
                {{-- Free Plan --}}
                <div class="bg-white rounded-2xl border border-gray-200 p-6 flex flex-col hover:border-gray-300 transition-colors">
                    <div class="mb-6">
                        <h3 class="font-bold text-gray-900 text-lg">Free</h3>
                        <p class="text-gray-500 text-sm mt-1">For casual readers</p>
                    </div>
                    <div class="mb-6">
                        <span class="text-4xl font-bold text-gray-900">₱0</span>
                        <span class="text-gray-400 text-sm">/month</span>
                    </div>
                    <ul class="text-sm text-gray-600 space-y-3 mb-8 flex-1">
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-green-500 text-xs"></i>
                            <span>Up to 3 ebooks</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-green-500 text-xs"></i>
                            <span>Free ebooks only</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-green-500 text-xs"></i>
                            <span>PDF, EPUB formats</span>
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" class="block text-center border-2 border-gray-300 text-gray-700 hover:border-gray-400 hover:bg-gray-50 font-semibold py-3 rounded-xl text-sm transition-all">
                        Get Started Free
                    </a>
                </div>
                
                {{-- Basic Plan --}}
                <div class="bg-white rounded-2xl border-2 border-blue-500 p-6 flex flex-col relative">
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-blue-500 text-white text-xs font-semibold px-3 py-1 rounded-full">
                        Popular
                    </div>
                    <div class="mb-6">
                        <h3 class="font-bold text-blue-600 text-lg">Basic</h3>
                        <p class="text-gray-500 text-sm mt-1">For book lovers</p>
                    </div>
                    <div class="mb-6">
                        <span class="text-4xl font-bold text-gray-900">₱99</span>
                        <span class="text-gray-400 text-sm">/month</span>
                    </div>
                    <ul class="text-sm text-gray-600 space-y-3 mb-8 flex-1">
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-blue-500 text-xs"></i>
                            <span>Up to 10 ebooks</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-blue-500 text-xs"></i>
                            <span>Free + Basic ebooks</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-blue-500 text-xs"></i>
                            <span>All formats supported</span>
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" class="block text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl text-sm transition-colors">
                        Subscribe Now
                    </a>
                </div>
                
                {{-- Premium Plan --}}
                <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl p-6 flex flex-col text-white">
                    <div class="mb-6">
                        <h3 class="font-bold text-lg">Premium</h3>
                        <p class="text-gray-400 text-sm mt-1">For avid readers</p>
                    </div>
                    <div class="mb-6">
                        <span class="text-4xl font-bold">₱199</span>
                        <span class="text-gray-400 text-sm">/month</span>
                    </div>
                    <ul class="text-sm text-gray-300 space-y-3 mb-8 flex-1">
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-yellow-400 text-xs"></i>
                            <span>Unlimited ebooks</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-yellow-400 text-xs"></i>
                            <span>All access levels</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-yellow-400 text-xs"></i>
                            <span>Audiobooks included</span>
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" class="block text-center bg-white text-gray-900 hover:bg-gray-100 font-bold py-3 rounded-xl text-sm transition-colors">
                        Go Premium
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="gradient-hero py-20 px-4 sm:px-6 relative overflow-hidden">
        <div class="absolute inset-0 shimmer opacity-30"></div>
        <div class="max-w-4xl mx-auto text-center relative z-10">
            <h2 class="text-3xl sm:text-4xl font-display font-bold text-white mb-4">Ready to Start Reading?</h2>
            <p class="text-lg text-gray-300 mb-8 max-w-xl mx-auto">Join thousands of readers discovering their next favorite book. No credit card required to get started.</p>
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="{{ route('register') }}" class="bg-white text-gray-900 font-bold px-8 py-4 rounded-xl hover:bg-gray-100 transition-all shadow-xl text-sm inline-flex items-center gap-2">
                    <i class="fas fa-rocket"></i>
                    Create Free Account
                </a>
                <a href="{{ route('login') }}" class="glass text-white font-semibold px-8 py-4 rounded-xl hover:bg-white/10 transition-all text-sm inline-flex items-center gap-2">
                    <i class="fas fa-sign-in-alt"></i>
                    Sign In
                </a>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-gray-900 text-gray-400 py-12 px-4 sm:px-6">
        <div class="max-w-7xl mx-auto">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 bg-gradient-to-br from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-book-open text-white text-sm"></i>
                        </div>
                        <span class="font-bold text-white text-lg">ELibrary</span>
                    </div>
                    <p class="text-sm text-gray-500 max-w-sm">Your gateway to unlimited knowledge. Access thousands of ebooks, audiobooks, and magazines from anywhere.</p>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4 text-sm">Quick Links</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#books" class="hover:text-white transition-colors">Browse Books</a></li>
                        <li><a href="#pricing" class="hover:text-white transition-colors">Pricing</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Login</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-white transition-colors">Register</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4 text-sm">Support</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors">Help Center</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Contact Us</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Terms of Service</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-sm text-gray-500">© {{ date('Y') }} ELibrary. All rights reserved.</p>
                <div class="flex items-center gap-4">
                    <a href="#" class="w-8 h-8 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-gray-700 transition-colors">
                        <i class="fab fa-twitter text-sm"></i>
                    </a>
                    <a href="#" class="w-8 h-8 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-gray-700 transition-colors">
                        <i class="fab fa-facebook-f text-sm"></i>
                    </a>
                    <a href="#" class="w-8 h-8 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-gray-700 transition-colors">
                        <i class="fab fa-instagram text-sm"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
