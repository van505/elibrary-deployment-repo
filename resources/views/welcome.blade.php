<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="ELibrary — Browse thousands of ebooks, borrow instantly, and read at your convenience.">
    <title>ELibrary &mdash; Your Digital Library</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .hero-gradient { background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 50%, #3b82f6 100%); }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

    {{-- ===================== NAVBAR ===================== --}}
    <nav class="bg-white shadow-sm border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            {{-- Logo --}}
            <a href="/" class="flex items-center gap-2.5">
                <div class="w-9 h-9 bg-blue-600 rounded-xl flex items-center justify-center shadow-sm">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <span class="font-bold text-gray-900 text-lg tracking-tight">ELibrary</span>
            </a>
            {{-- Nav links --}}
            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}"
                   class="text-gray-600 hover:text-blue-600 text-sm font-medium px-4 py-2 rounded-lg hover:bg-gray-100 transition-colors">
                    Login
                </a>
                <a href="{{ route('register') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors shadow-sm">
                    Register
                </a>
            </div>
        </div>
    </nav>

    {{-- ===================== HERO ===================== --}}
    <section class="hero-gradient text-white">
        <div class="max-w-5xl mx-auto px-6 py-28 text-center">
            <span class="inline-block bg-white/20 text-white text-xs font-semibold px-3 py-1 rounded-full mb-6 tracking-wide uppercase">
                Digital Library System
            </span>
            <h1 class="text-5xl font-bold mb-6 leading-tight">
                Your Digital Library,<br>Anytime Anywhere
            </h1>
            <p class="text-xl text-blue-100 mb-10 max-w-2xl mx-auto leading-relaxed">
                Browse thousands of ebooks, borrow instantly, and read at your convenience.
                Your next great read is just a click away.
            </p>
            <div class="flex gap-4 justify-center flex-wrap">
                <a href="{{ route('register') }}"
                   class="bg-white text-blue-700 font-semibold px-8 py-3.5 rounded-xl hover:bg-blue-50 transition-colors shadow-lg text-sm">
                    Get Started Free
                </a>
                <a href="{{ route('login') }}"
                   class="border-2 border-white/60 text-white font-semibold px-8 py-3.5 rounded-xl hover:bg-white/10 transition-colors text-sm">
                    Browse Ebooks
                </a>
            </div>
        </div>
    </section>

    {{-- ===================== FEATURES ===================== --}}
    <section class="py-20 px-6">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-3xl font-bold text-gray-800 text-center mb-4">Why Choose ELibrary?</h2>
            <p class="text-gray-500 text-center mb-14 max-w-xl mx-auto">Everything you need from a modern digital library, built for readers like you.</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                {{-- Feature 1 --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center hover:shadow-md transition-shadow">
                    <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-5">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Instant Access</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Borrow ebooks instantly and start reading in seconds. No waiting, no queues.</p>
                </div>

                {{-- Feature 2 --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center hover:shadow-md transition-shadow">
                    <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-5">
                        <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Easy Returns</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Return ebooks with a single click when you're done. Simple and hassle-free.</p>
                </div>

                {{-- Feature 3 --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center hover:shadow-md transition-shadow">
                    <div class="w-14 h-14 bg-purple-100 rounded-2xl flex items-center justify-center mx-auto mb-5">
                        <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Smart Reservations</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Reserve your next read before it runs out. Get notified when it's ready for you.</p>
                </div>

            </div>
        </div>
    </section>

    {{-- ===================== CTA BANNER ===================== --}}
    <section class="bg-blue-600 py-16 px-6">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-3xl font-bold text-white mb-4">Ready to start reading?</h2>
            <p class="text-blue-100 mb-8 text-lg">Join hundreds of members already borrowing from our growing catalog.</p>
            <a href="{{ route('register') }}"
               class="inline-block bg-white text-blue-700 font-semibold px-8 py-3.5 rounded-xl hover:bg-blue-50 transition-colors shadow-lg text-sm">
                Create a Free Account
            </a>
        </div>
    </section>

    {{-- ===================== FOOTER ===================== --}}
    <footer class="bg-slate-800 text-slate-400 py-8 px-6">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 bg-blue-600 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <span class="font-semibold text-white text-sm">ELibrary</span>
            </div>
            <p class="text-xs">&copy; {{ date('Y') }} ELibrary. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
