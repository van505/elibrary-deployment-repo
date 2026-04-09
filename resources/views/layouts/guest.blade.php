<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ELibrary &mdash; Access Your Account</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style> 
        body { font-family: 'Inter', sans-serif; background-color: #F7F7F7; color: #333333; }
        .text-primary { color: #3A69A1; }
        .bg-primary { background-color: #3A69A1; }
        .border-primary { border-color: #3A69A1; }
        .hover-bg-primary:hover { background-color: #2F5685; }
        
        .card-shadow { box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05); }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col items-center justify-center py-10 px-4">

    {{-- Logo --}}
    <div class="mb-8">
        <a href="/" class="flex items-center gap-2 group">
            <div class="w-10 h-10 rounded bg-primary flex items-center justify-center transition-transform group-hover:scale-105">
                <i class="fas fa-book-open text-white text-sm"></i>
            </div>
            <span class="font-bold text-2xl tracking-tight text-[#333333]">ELibrary</span>
        </a>
    </div>

    {{-- Form Container --}}
    <div class="w-full max-w-md bg-white rounded-2xl p-8 card-shadow border border-gray-100">
        
        <div class="text-center mb-8">
            @if(request()->routeIs('login'))
                <h1 class="text-2xl font-bold text-[#333333] mb-2">Welcome Back</h1>
                <p class="text-sm text-[#777777]">Please enter your details to sign in.</p>
            @elseif(request()->routeIs('register'))
                <h1 class="text-2xl font-bold text-[#333333] mb-2">Create Account</h1>
                <p class="text-sm text-[#777777]">Start your unlimited reading journey.</p>
            @endif
        </div>

        {{-- Breeze Slot --}}
        <div>
            {{ $slot }}
        </div>
        
    </div>

    {{-- Footer Links --}}
    <div class="mt-8 text-center text-sm text-[#777777]">
        @if(request()->routeIs('login'))
            Don't have an account? <a href="{{ route('register') }}" class="font-medium text-primary hover:underline">Sign up free</a>
        @elseif(request()->routeIs('register'))
            Already have an account? <a href="{{ route('login') }}" class="font-medium text-primary hover:underline">Log in</a>
        @endif
    </div>

</body>
</html>
