<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ELibrary &mdash; {{ config('app.name', 'ELibrary') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">

    {{-- Navbar --}}
    <nav class="bg-white shadow-sm border-b border-gray-100 sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <span class="font-bold text-gray-900 text-lg">ELibrary</span>
            </a>
            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-600 text-sm font-medium px-4 py-2 rounded-lg hover:bg-gray-100 transition-colors">Login</a>
                <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">Register</a>
            </div>
        </div>
    </nav>

    {{-- Breeze passes form content via $slot (x-guest-layout component) --}}
    <main class="flex items-center justify-center min-h-[calc(100vh-73px)] py-10">
        {{ $slot }}
    </main>

</body>
</html>
