<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ELibrary — Subscribe. Read. Enjoy.</title>
    <meta name="description" content="Access thousands of ebooks with a single subscription. Choose from Free, Basic, or Premium plans.">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #1e3a5f 0%, #1d4ed8 50%, #7c3aed 100%); }
        .glass { background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2); }
        @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-10px)} }
        .float { animation: float 3s ease-in-out infinite; }
    </style>
</head>
<body class="bg-white">

    {{-- Navbar --}}
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/90 backdrop-blur-sm border-b border-gray-100 px-6 py-4">
        <div class="max-w-6xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13"/></svg>
                </div>
                <span class="font-bold text-gray-800 text-lg">ELibrary</span>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-600 text-sm font-medium transition-colors">Sign In</a>
                <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">Get Started Free</a>
            </div>
        </div>
    </nav>

    {{-- Hero --}}
    <section class="gradient-bg min-h-screen flex items-center justify-center px-6 pt-20">
        <div class="max-w-4xl mx-auto text-center">
            <div class="glass inline-block px-4 py-2 rounded-full text-white text-xs font-semibold mb-6 tracking-wide">
                ✨ Digital Subscription Platform
            </div>
            <h1 class="text-5xl md:text-7xl font-extrabold text-white leading-tight mb-6">
                Subscribe.<br>Read.<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-purple-200">Enjoy.</span>
            </h1>
            <p class="text-xl text-blue-100 mb-10 max-w-2xl mx-auto">
                Access thousands of ebooks with a single subscription. PDF, EPUB, and Audiobooks — read anything, anytime.
            </p>
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="{{ route('register') }}" class="bg-white text-blue-700 font-bold px-8 py-4 rounded-xl hover:bg-blue-50 transition-all shadow-lg text-sm">
                    Start Free — No Credit Card
                </a>
                <a href="{{ route('login') }}" class="glass text-white font-semibold px-8 py-4 rounded-xl hover:bg-white/20 transition-all text-sm">
                    Sign In
                </a>
            </div>
        </div>
    </section>

    {{-- Features --}}
    <section class="bg-white py-24 px-6">
        <div class="max-w-5xl mx-auto text-center mb-16">
            <h2 class="text-3xl font-bold text-gray-800">Everything you need to read more</h2>
            <p class="text-gray-500 mt-3">One subscription. Thousands of books.</p>
        </div>
        <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center p-6">
                <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Flexible Plans</h3>
                <p class="text-gray-500 text-sm">Free, Basic, and Premium plans to fit every reader. Upgrade anytime.</p>
            </div>
            <div class="text-center p-6">
                <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Instant Access</h3>
                <p class="text-gray-500 text-sm">Start reading immediately. No waiting, no queues, no due dates.</p>
            </div>
            <div class="text-center p-6">
                <div class="w-14 h-14 bg-purple-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Any Format</h3>
                <p class="text-gray-500 text-sm">PDF, EPUB, and MP3 Audiobooks — read and listen in any format.</p>
            </div>
        </div>
    </section>

    {{-- Pricing Preview --}}
    <section class="bg-gray-50 py-24 px-6">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-14">
                <h2 class="text-3xl font-bold text-gray-800">Simple, transparent pricing</h2>
                <p class="text-gray-500 mt-3">Start free. Upgrade when you're ready.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Free --}}
                <div class="bg-white rounded-2xl border border-gray-200 p-7 flex flex-col">
                    <h3 class="font-bold text-gray-800 text-lg">Free</h3>
                    <div class="mt-2 mb-4"><span class="text-4xl font-extrabold text-gray-900">₱0</span><span class="text-gray-400 text-sm">/month</span></div>
                    <ul class="text-sm text-gray-600 space-y-2 mb-8 flex-1">
                        <li>✅ Up to 3 ebooks</li>
                        <li>✅ Free ebooks only</li>
                        <li>✅ PDF, EPUB, MP3</li>
                    </ul>
                    <a href="{{ route('register') }}" class="block text-center border-2 border-gray-300 text-gray-700 hover:bg-gray-50 font-semibold py-2.5 rounded-xl text-sm transition-colors">Get Started</a>
                </div>
                {{-- Basic --}}
                <div class="bg-white rounded-2xl border-2 border-blue-400 p-7 flex flex-col">
                    <h3 class="font-bold text-blue-700 text-lg">Basic</h3>
                    <div class="mt-2 mb-4"><span class="text-4xl font-extrabold text-gray-900">₱99</span><span class="text-gray-400 text-sm">/month</span></div>
                    <ul class="text-sm text-gray-600 space-y-2 mb-8 flex-1">
                        <li>✅ Up to 10 ebooks</li>
                        <li>✅ Free + Basic ebooks</li>
                        <li>✅ PDF, EPUB, MP3</li>
                    </ul>
                    <a href="{{ route('register') }}" class="block text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-xl text-sm transition-colors">Subscribe</a>
                </div>
                {{-- Premium --}}
                <div class="bg-gradient-to-br from-purple-600 to-blue-600 rounded-2xl p-7 flex flex-col text-white relative">
                    <span class="absolute -top-3 left-1/2 -translate-x-1/2 bg-yellow-400 text-yellow-900 text-xs font-bold px-3 py-0.5 rounded-full">Most Popular</span>
                    <h3 class="font-bold text-lg">Premium</h3>
                    <div class="mt-2 mb-4"><span class="text-4xl font-extrabold">₱199</span><span class="text-white/70 text-sm">/month</span></div>
                    <ul class="text-sm text-white/90 space-y-2 mb-8 flex-1">
                        <li>✅ Unlimited ebooks</li>
                        <li>✅ All access levels</li>
                        <li>✅ PDF, EPUB, MP3</li>
                    </ul>
                    <a href="{{ route('register') }}" class="block text-center bg-white text-purple-700 hover:bg-purple-50 font-bold py-2.5 rounded-xl text-sm transition-colors">Subscribe</a>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="gradient-bg py-20 px-6 text-center">
        <h2 class="text-3xl font-bold text-white mb-4">Start reading today — it's free</h2>
        <p class="text-blue-100 mb-8">No credit card required. Upgrade whenever you want.</p>
        <a href="{{ route('register') }}" class="bg-white text-blue-700 font-bold px-8 py-4 rounded-xl hover:bg-blue-50 transition-all shadow-lg text-sm inline-block">
            Create Free Account
        </a>
    </section>

    {{-- Footer --}}
    <footer class="bg-gray-900 text-gray-500 text-center py-8 text-sm">
        © {{ date('Y') }} ELibrary. All rights reserved.
    </footer>

</body>
</html>
