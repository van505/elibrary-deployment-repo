<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Authentication') — ELibrary</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Playfair Display', serif; }
        .gradient-bg { background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #312e81 100%); }
        .glass { background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2); }
        @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-10px)} }
        .float { animation: float 4s ease-in-out infinite; }
        .input-focus:focus { box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1); }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">

    <div class="min-h-screen flex">
        {{-- Left Side - Image/Branding --}}
        <div class="hidden lg:flex lg:w-1/2 gradient-bg relative overflow-hidden">
            {{-- Background Effects --}}
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute -top-40 -right-40 w-96 h-96 bg-blue-500/30 rounded-full blur-3xl"></div>
                <div class="absolute bottom-20 -left-20 w-72 h-72 bg-purple-500/30 rounded-full blur-3xl"></div>
            </div>
            
            {{-- Floating Books Decoration --}}
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="relative w-80 h-80">
                    <div class="absolute top-0 left-10 w-28 h-40 bg-gradient-to-br from-amber-400 to-orange-500 rounded-lg shadow-2xl float" style="transform: rotate(-12deg);"></div>
                    <div class="absolute top-10 right-10 w-28 h-40 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-lg shadow-2xl float" style="transform: rotate(8deg); animation-delay: 0.5s;"></div>
                    <div class="absolute bottom-10 left-20 w-28 h-40 bg-gradient-to-br from-rose-400 to-pink-500 rounded-lg shadow-2xl float" style="transform: rotate(-5deg); animation-delay: 1s;"></div>
                    <div class="absolute bottom-0 right-20 w-28 h-40 bg-gradient-to-br from-violet-400 to-purple-500 rounded-lg shadow-2xl float" style="transform: rotate(10deg); animation-delay: 1.5s;"></div>
                </div>
            </div>
            
            {{-- Content --}}
            <div class="absolute bottom-0 left-0 right-0 p-12">
                <div class="glass rounded-2xl p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-book-open text-white"></i>
                        </div>
                        <span class="font-bold text-white text-xl">ELibrary</span>
                    </div>
                    <h3 class="text-white text-lg font-semibold mb-2">Your Digital Reading Companion</h3>
                    <p class="text-gray-300 text-sm">Access thousands of ebooks, audiobooks, and magazines. Start your reading journey today.</p>
                </div>
            </div>
            
            {{-- Back to Home Link --}}
            <div class="absolute top-6 left-6">
                <a href="/" class="glass text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-white/20 transition-colors inline-flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    Back to Home
                </a>
            </div>
        </div>

        {{-- Right Side - Form --}}
        <div class="w-full lg:w-1/2 flex flex-col">
            {{-- Mobile Navbar --}}
            <div class="lg:hidden bg-white border-b border-gray-100 px-4 py-3">
                <div class="flex items-center justify-between">
                    <a href="/" class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-gradient-to-br from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-book-open text-white text-sm"></i>
                        </div>
                        <span class="font-bold text-gray-900">ELibrary</span>
                    </a>
                    <a href="/" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
            </div>

            {{-- Form Container --}}
            <div class="flex-1 flex items-center justify-center p-6 sm:p-10">
                <div class="w-full max-w-md">
                    {{ $slot }}
                </div>
            </div>
            
            {{-- Footer --}}
            <div class="p-6 text-center">
                <p class="text-gray-400 text-xs">
                    © {{ date('Y') }} ELibrary. All rights reserved.
                </p>
            </div>
        </div>
    </div>

</body>
</html>
