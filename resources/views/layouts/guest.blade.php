<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ELibrary &mdash; @yield('title', 'Access Your Account')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { font-family: 'Inter', sans-serif; }

        /* Left panel gradient with background image */
        .auth-left-panel {
            background-color: #1e3a8a;
            background-image: linear-gradient(to bottom right, rgba(30, 64, 175, 0.85), rgba(15, 23, 42, 0.95)), url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?q=80&w=1000&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-blend-mode: multiply;
            position: relative;
        }

        /* Decorative blobs on the left panel (optional subtle background) */
        .blob-1 {
            position: absolute;
            bottom: -100px;
            right: -100px;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: rgba(255,255,255,0.08);
        }
        .blob-2 {
            position: absolute;
            top: -50px;
            right: 50px;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: rgba(255,255,255,0.06);
        }

        /* Right panel form inputs */
        .auth-input {
            width: 100%;
            border: 1.5px solid #e5e7eb;
            border-radius: 0.75rem; /* rounded-xl */
            padding: 11px 16px;
            font-size: 0.95rem;
            color: #111827;
            background: #f9fafb; /* bg-gray-50 */
            outline: none;
            transition: all 0.2s ease-in-out;
        }
        .auth-input:focus {
            background: #ffffff;
            border-color: #3b82f6; /* blue-500 */
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15); /* ring-blue-500 */
        }
        
        .auth-btn {
            width: 100%;
            background: #2563eb;
            color: #fff;
            font-weight: 600;
            font-size: 0.95rem;
            padding: 12px;
            border-radius: 0.75rem; /* rounded-xl */
            border: none;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
        }
        .auth-btn:hover { 
            background: #1d4ed8; 
            transform: translateY(-2px);
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.25);
        }
        .auth-btn:active { transform: translateY(1px); }
        .auth-btn:disabled { opacity: 0.65; cursor: not-allowed; }

        /* Scrollable right panel for register */
        .auth-right-scroll {
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 transparent;
        }
        .auth-right-scroll::-webkit-scrollbar { width: 5px; }
        .auth-right-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
    </style>
</head>
<body class="min-h-screen bg-white text-gray-900">

    <div class="flex min-h-screen flex-col lg:flex-row">

        {{-- ===== LEFT PANEL (Desktop only) ===== --}}
        <div class="auth-left-panel hidden lg:flex flex-col justify-between w-full lg:w-[45%] flex-shrink-0 p-12 overflow-hidden">
            {{-- Decorative layout (removing blobs overlaying the image) --}}

            <div class="relative z-10 w-full max-w-md mx-auto h-full flex flex-col justify-between">
                <div>
                    {{-- Logo part --}}
                    {{-- Left panel content specific to route --}}
                    @if(isset($leftPanel))
                        {{ $leftPanel }}
                    @endif
                </div>

                {{-- Bottom link --}}
                <div class="mt-12 text-sm text-blue-100 font-medium">
                    @if(isset($bottomLink))
                        {{ $bottomLink }}
                    @endif
                </div>
            </div>
        </div>

        {{-- ===== RIGHT PANEL ===== --}}
        <div class="flex-1 auth-right-scroll flex flex-col lg:w-[55%] relative">
            
            {{-- Back to Home Link --}}
            <div class="absolute top-6 right-6 lg:top-8 lg:right-10 z-50">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-blue-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Home
                </a>
            </div>

            <div class="flex-1 flex flex-col items-center justify-center mt-12 mb-12 p-6 lg:p-12 w-full max-w-md mx-auto">

                {{-- Mobile Logo/Header Area --}}
                <div class="lg:hidden w-full text-center mb-8">
                     <p class="text-3xl font-bold text-blue-700 tracking-tight">ELibrary</p>
                     <p class="text-sm text-gray-500 mt-1">Your Digital Reading Companion</p>
                </div>

                {{-- Desktop Logo --}}
                <div class="hidden lg:flex w-full justify-center mb-8">
                     <div class="text-center">
                         <p class="text-3xl font-bold text-blue-700 tracking-tight">ELibrary</p>
                         <p class="text-xs text-gray-500 mt-1 uppercase tracking-widest font-semibold">Your Digital Reading Companion</p>
                     </div>
                </div>

                {{-- Form Slot --}}
                <div class="w-full">
                    {{ $slot }}
                </div>
            </div>
        </div>

    </div>

</body>
</html>
