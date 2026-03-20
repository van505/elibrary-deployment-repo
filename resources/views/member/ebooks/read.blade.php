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
    <header class="bg-gray-800 border-b border-gray-700 px-6 py-3 flex items-center justify-between flex-shrink-0">
        <div class="flex items-center gap-4">
            <a href="{{ route('member.ebooks.show', $ebook->id) }}" class="text-gray-400 hover:text-white transition-colors" title="Back">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-white font-semibold text-sm truncate max-w-xs">{{ $ebook->title }}</h1>
                <p class="text-gray-400 text-xs">{{ $ebook->authors->pluck('name')->join(', ') }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <span class="bg-gray-700 text-gray-300 text-xs px-2 py-1 rounded">{{ strtoupper($ebook->file_type) }}</span>
            <form action="{{ route('member.ebooks.remove-access', $ebook->id) }}" method="POST">
                @csrf @method('DELETE')
                <button type="submit" onclick="return confirm('Remove from reading list?')"
                        class="text-xs text-red-400 hover:text-red-300 border border-red-700 hover:border-red-500 px-3 py-1.5 rounded-lg transition-colors">
                    Remove
                </button>
            </form>
        </div>
    </header>

    {{-- Reader --}}
    <main class="flex-1 overflow-hidden">
        @if($ebook->file_type === 'mp3')
            <div class="flex items-center justify-center h-full">
                <div class="bg-gray-800 rounded-2xl p-10 text-center max-w-md">
                    <svg class="w-20 h-20 text-blue-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
                    <h2 class="text-white font-bold text-lg mb-2">{{ $ebook->title }}</h2>
                    <audio controls class="w-full mt-4">
                        <source src="{{ asset('storage/' . $ebook->file_path) }}" type="audio/mpeg">
                        Your browser does not support audio playback.
                    </audio>
                </div>
            </div>
        @else
            <iframe
                src="https://docs.google.com/viewer?url={{ urlencode(request()->getSchemeAndHttpHost() . '/storage/' . $ebook->file_path) }}&embedded=true"
                class="w-full h-full border-0"
                title="{{ $ebook->title }}">
            </iframe>
        @endif
    </main>

</body>
</html>
