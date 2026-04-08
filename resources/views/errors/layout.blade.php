<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('code') — @yield('title') | ELibrary</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4">
    <div class="text-center max-w-md mx-auto">
        <div class="mb-8">
            <div class="flex items-center justify-center gap-3 mb-6">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <span class="text-gray-800 font-bold text-xl">ELibrary</span>
            </div>
        </div>

        <div class="@yield('icon-bg', 'bg-red-50') rounded-full w-24 h-24 flex items-center justify-center mx-auto mb-6">
            @yield('icon')
        </div>

        <p class="text-6xl font-bold @yield('code-color', 'text-red-500') mb-3">@yield('code')</p>
        <h1 class="text-2xl font-bold text-gray-800 mb-3">@yield('title')</h1>
        <p class="text-gray-500 mb-8">@yield('message')</p>

        <div class="flex justify-center gap-3 flex-wrap">
            <button onclick="history.back()" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium px-5 py-2.5 rounded-lg text-sm transition-colors">
                ← Go Back
            </button>
            @yield('extra-actions')
            @auth
            <a href="{{ route('dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-lg text-sm transition-colors">
                Go to Dashboard
            </a>
            @else
            <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-lg text-sm transition-colors">
                Login
            </a>
            @endauth
        </div>
    </div>
</body>
</html>
