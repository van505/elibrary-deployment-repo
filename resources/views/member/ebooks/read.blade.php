<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $ebook->title }} — ELibrary Reader</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-900 flex flex-col h-screen">

    {{-- Top Bar --}}
    <header class="bg-gray-800 border-b border-gray-700 px-4 py-3 flex items-center justify-between flex-shrink-0" style="height:60px">
        <div class="flex items-center gap-4">
            <a href="{{ route('member.ebooks.show', $ebook->id) }}" class="text-gray-400 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-white font-semibold text-sm truncate max-w-xs">{{ $ebook->title }}</h1>
                <p class="text-gray-400 text-xs">{{ $ebook->authors->pluck('name')->join(', ') }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <span class="bg-gray-700 text-gray-300 text-xs px-2 py-1 rounded">{{ strtoupper($ebook->file_type) }}</span>

            {{-- Download button --}}
            <a href="{{ Storage::url($ebook->file_path) }}" download
               class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium px-3 py-1.5 rounded-lg transition-colors">
                ⬇ Download
            </a>

            <form action="{{ route('member.ebooks.remove-access', $ebook->id) }}" method="POST">
                @csrf @method('DELETE')
                <button type="submit"
                        onclick="return confirm('Remove from reading list?')"
                        class="text-xs text-red-400 hover:text-red-300 border border-red-700 hover:border-red-500 px-3 py-1.5 rounded-lg transition-colors">
                    Remove
                </button>
            </form>
        </div>
    </header>

    {{-- Reader --}}
    <main class="flex-1 overflow-hidden">
        @if($ebook->file_type === 'mp3')
            {{-- Audio player --}}
            <div class="flex items-center justify-center h-full">
                <div class="bg-gray-800 rounded-2xl p-10 text-center max-w-md w-full mx-4">
                    <svg class="w-20 h-20 text-blue-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                    </svg>
                    <h2 class="text-white font-bold text-lg mb-1">{{ $ebook->title }}</h2>
                    <p class="text-gray-400 text-sm mb-6">{{ $ebook->authors->pluck('name')->join(', ') }}</p>
                    <audio controls class="w-full">
                        <source src="{{ Storage::url($ebook->file_path) }}" type="audio/mpeg">
                        Your browser does not support audio playback.
                    </audio>
                </div>
            </div>
        @else
            {{-- PDF / EPUB — use browser's built-in viewer via direct storage URL --}}
            <iframe
                src="{{ Storage::url($ebook->file_path) }}"
                class="w-full border-0"
                style="height: calc(100vh - 60px);"
                title="{{ $ebook->title }}">
                <div class="flex items-center justify-center h-full text-white p-10 text-center">
                    <div>
                        <p class="mb-4">Your browser cannot display this file inline.</p>
                        <a href="{{ Storage::url($ebook->file_path) }}"
                           class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                            Download {{ strtoupper($ebook->file_type) }}
                        </a>
                    </div>
                </div>
            </iframe>
        @endif
    </main>

</body>
</html>
