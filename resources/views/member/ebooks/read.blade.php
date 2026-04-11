<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $ebook->title }} — ELibrary Reader</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }

        /* Preview bar shimmer */
        @keyframes shimmer {
            0% { background-position: -200% center; }
            100% { background-position: 200% center; }
        }
        .preview-shimmer {
            background: linear-gradient(90deg, #fbbf24 25%, #f59e0b 50%, #fbbf24 75%);
            background-size: 200% auto;
            animation: shimmer 2.5s linear infinite;
        }
    </style>
</head>
<body class="bg-gray-900 flex flex-col h-screen">

    @php $isPreview   = $isPreview   ?? false; @endphp
    @php $previewPages = $previewPages ?? 0; @endphp

    {{-- Preview Warning Bar --}}
    @if($isPreview)
    <div id="preview-bar"
         class="preview-shimmer w-full flex-shrink-0 px-4 flex-col flex-shrink-0"
         style="min-height:52px;">
        <div class="flex flex-wrap items-center justify-between gap-3 px-0 py-2">
            <div class="flex items-center gap-2 text-gray-900">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01M12 3a9 9 0 110 18A9 9 0 0112 3z"/>
                </svg>
                <span class="text-sm font-semibold">
                    Preview Mode — First <strong>{{ $previewPages }} pages</strong>.
                    <span id="preview-timer" class="ml-1 font-mono text-xs bg-black/20 px-1.5 py-0.5 rounded"></span>
                </span>
            </div>
            <a href="{{ route('member.subscriptions.index') }}"
               class="flex-shrink-0 bg-gray-900 hover:bg-gray-800 text-yellow-400 border border-yellow-400 text-xs font-bold px-4 py-1.5 rounded-full transition-colors whitespace-nowrap">
                🔓 Subscribe to Read Full Book
            </a>
        </div>
        {{-- Progress bar --}}
        <div class="w-full h-1 bg-black/20 rounded-full overflow-hidden mb-1">
            <div id="preview-progress" class="h-full bg-gray-900/50 rounded-full transition-all duration-1000" style="width:0%"></div>
        </div>
    </div>
    @endif


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

            @if($isPreview)
                <span class="bg-yellow-500 text-gray-900 text-xs font-bold px-2 py-1 rounded">Preview</span>
            @endif

            {{-- Download link (only for non-preview) --}}
            @if(!$isPreview && ($fileUrl ?? null))
                <a href="{{ $fileUrl }}" download
                   class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium px-3 py-1.5 rounded-lg transition-colors">
                    ⬇ Download
                </a>
            @endif

            @if(!$isPreview)
            <form action="{{ route('member.ebooks.remove-access', $ebook->id) }}" method="POST">
                @csrf @method('DELETE')
                <button type="submit"
                        onclick="return confirm('Remove from reading list?')"
                        class="text-xs text-red-400 hover:text-red-300 border border-red-700 hover:border-red-500 px-3 py-1.5 rounded-lg transition-colors">
                    Remove
                </button>
            </form>
            @endif
        </div>
    </header>

    {{-- Reader --}}
    <main class="flex-1 overflow-hidden relative">

        {{-- Preview blur overlay (shown in preview mode over the iframe) --}}
        @if($isPreview)
        <div id="preview-overlay"
             class="absolute inset-0 z-20 flex-col items-end justify-end hidden"
             style="background: linear-gradient(to top, rgba(17,24,39,0.98) 0%, rgba(17,24,39,0.85) 30%, transparent 60%);">
            <div class="absolute bottom-0 left-0 right-0 p-8 text-center">
                <div class="inline-block bg-gray-800 border border-gray-600 rounded-2xl px-8 py-6 max-w-md mx-auto shadow-2xl">
                    <div class="text-4xl mb-3">📖</div>
                    <h2 class="text-white font-bold text-xl mb-2">Preview Complete</h2>
                    <p class="text-gray-300 text-sm mb-5">
                        You've reached the end of your <strong class="text-yellow-400">{{ $previewPages }}-page preview</strong>.<br>
                        Subscribe to continue reading the full book.
                    </p>
                    <a href="{{ route('member.subscriptions.index') }}"
                       class="block w-full bg-gradient-to-r from-yellow-400 to-orange-400 hover:from-yellow-300 hover:to-orange-300 text-gray-900 font-bold px-6 py-3 rounded-xl transition-all transform hover:scale-105 shadow-lg mb-3">
                        🔓 Subscribe Now
                    </a>
                    <a href="{{ route('member.ebooks.show', $ebook->id) }}"
                       class="text-gray-400 hover:text-gray-200 text-sm transition-colors">
                        ← Back to book details
                    </a>
                </div>
            </div>
        </div>
        @endif

        @if($ebook->file_type === 'mp3')
            {{-- Audio player --}}
            <div class="flex items-center justify-center h-full">
                <div class="bg-gray-800 rounded-2xl p-10 text-center max-w-md w-full mx-4">
                    <svg class="w-20 h-20 text-blue-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                    </svg>
                    <h2 class="text-white font-bold text-lg mb-1">{{ $ebook->title }}</h2>
                    <p class="text-gray-400 text-sm mb-6">{{ $ebook->authors->pluck('name')->join(', ') }}</p>
                    @if($fileUrl ?? null)
                        <audio controls class="w-full">
                            <source src="{{ $fileUrl }}" type="audio/mpeg">
                            Your browser does not support audio playback.
                        </audio>
                    @else
                        <p class="text-red-400 text-sm">Audio file not available. Please contact the administrator.</p>
                    @endif

                    @if($isPreview)
                    <div class="mt-6 p-4 bg-yellow-900/30 border border-yellow-500/30 rounded-xl text-sm text-yellow-300">
                        🎧 Preview mode — subscribe to unlock the full audiobook.
                    </div>
                    @endif
                </div>
            </div>

        @elseif($fileUrl ?? null)
            {{-- PDF / EPUB — direct public URL --}}
            <iframe
                id="reader-iframe"
                src="{{ $fileUrl }}{{ $isPreview ? '#page=1' : '' }}"
                class="w-full border-0"
                style="height: calc(100vh - {{ $isPreview ? '48px + ' : '' }}60px);"
                title="{{ $ebook->title }}">
                <div class="flex items-center justify-center h-full text-white p-10 text-center">
                    <div>
                        <p class="mb-4">Your browser cannot display this file inline.</p>
                        <a href="{{ $fileUrl }}"
                           class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                            Download {{ strtoupper($ebook->file_type) }}
                        </a>
                    </div>
                </div>
            </iframe>

        @else
            {{-- File not found --}}
            <div class="flex items-center justify-center h-full text-center text-white p-10">
                <div>
                    <p class="text-2xl mb-2">📄</p>
                    <p class="text-lg font-medium mb-2">File not available</p>
                    <p class="text-gray-400 text-sm">The ebook file could not be located. Please contact the administrator.</p>
                    <a href="{{ route('member.ebooks.show', $ebook->id) }}" class="mt-4 inline-block text-blue-400 hover:underline text-sm">← Back to ebook details</a>
                </div>
            </div>
        @endif
    </main>

@if($isPreview)
<script>
    (function () {
        const previewPages  = {{ (int) $previewPages }};
        // Allow ~8 seconds per page, capped at 90 seconds
        const totalSeconds  = Math.min(previewPages * 8, 90);
        let   remaining     = totalSeconds;

        const overlay       = document.getElementById('preview-overlay');
        const iframe        = document.getElementById('reader-iframe');
        const progressBar   = document.getElementById('preview-progress');
        const timerDisplay  = document.getElementById('preview-timer');

        function showOverlay() {
            if (!overlay) return;
            overlay.classList.remove('hidden');
            overlay.classList.add('flex');
            if (iframe) {
                iframe.style.filter        = 'blur(8px)';
                iframe.style.pointerEvents = 'none';
            }
            // Clear the countdown
            if (window._previewInterval) clearInterval(window._previewInterval);
        }

        // Countdown tick every second
        window._previewInterval = setInterval(() => {
            remaining--;

            // Update visual progress bar
            if (progressBar) {
                const pct = ((totalSeconds - remaining) / totalSeconds) * 100;
                progressBar.style.width = pct + '%';
            }
            if (timerDisplay) {
                timerDisplay.textContent = remaining > 0 ? remaining + 's' : '';
            }

            if (remaining <= 0) {
                showOverlay();
            }
        }, 1000);

        // Also show overlay if user clicks into the iframe (tries to browse more pages)
        // We detect when the iframe gains focus — meaning user clicked inside it
        let iframeClickCount = 0;
        window.addEventListener('blur', () => {
            // Window blur likely means focus went into iframe
            iframeClickCount++;
            // After 3 interactions (page turns), show overlay early
            if (iframeClickCount > 3 && remaining < totalSeconds * 0.6) {
                showOverlay();
            }
        });
    })();
</script>
@endif

</body>
</html>
