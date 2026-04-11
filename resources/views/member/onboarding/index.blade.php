<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to ELibrary — Get Started</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .category-card.selected {
            border-color: #2563eb;
            background-color: #eff6ff;
            color: #1d4ed8;
        }
        .category-card.selected .cat-icon {
            color: #2563eb;
        }
        .category-card.selected .check-icon {
            display: flex;
        }
        .check-icon { display: none; }
        .avatar-preview { object-fit: cover; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

    {{-- ── TOP BAR ─── --}}
    <header class="w-full bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between flex-shrink-0">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <span class="text-gray-900 font-bold text-lg">ELibrary</span>
        </div>
        @if($step < 3)
        <form action="{{ route('member.onboarding.skip') }}" method="POST">
            @csrf
            <button type="submit" class="text-sm text-gray-400 hover:text-gray-600 transition-colors">
                Skip setup →
            </button>
        </form>
        @endif
    </header>

    {{-- ── PROGRESS BAR ─── --}}
    <div class="w-full bg-white border-b border-gray-100 px-6 py-4">
        <div class="max-w-lg mx-auto">
            {{-- Step Labels --}}
            <div class="flex items-center justify-between mb-3">
                @php
                    $steps = ['Profile', 'Interests', 'Done'];
                @endphp
                @foreach($steps as $i => $label)
                    @php $num = $i + 1; @endphp
                    <div class="flex items-center gap-2 {{ $num < $step ? 'text-blue-600' : ($num === $step ? 'text-gray-900' : 'text-gray-400') }}">
                        <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold
                            {{ $num < $step ? 'bg-blue-600 text-white' : ($num === $step ? 'bg-gray-900 text-white' : 'bg-gray-200 text-gray-500') }}">
                            @if($num < $step)
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            @else
                                {{ $num }}
                            @endif
                        </div>
                        <span class="text-sm font-semibold hidden sm:block">{{ $label }}</span>
                    </div>
                    @if($i < 2)
                        <div class="flex-1 h-1 mx-3 rounded {{ $num < $step ? 'bg-blue-600' : 'bg-gray-200' }}"></div>
                    @endif
                @endforeach
            </div>
            {{-- Overall progress bar --}}
            <div class="w-full bg-gray-200 rounded-full h-1.5">
                <div class="bg-blue-600 h-1.5 rounded-full transition-all duration-500" style="width: {{ (($step - 1) / 2) * 100 }}%"></div>
            </div>
            <p class="text-xs text-gray-400 mt-1.5 text-center">Step {{ $step }} of 3</p>
        </div>
    </div>

    {{-- ── CONTENT ─── --}}
    <main class="flex-1 flex items-start justify-center p-6 pt-10">
        <div class="w-full max-w-lg">

            {{-- ===== STEP 1: PROFILE ===== --}}
            @if($step === 1)
            <div class="text-center mb-8">
                <div class="text-4xl mb-3">👋</div>
                <h1 class="text-2xl font-extrabold text-gray-900">Welcome to ELibrary!</h1>
                <p class="text-gray-500 mt-2">Let's set up your account to get started.</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                <form action="{{ route('member.onboarding.step1') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    {{-- Avatar Upload --}}
                    <div class="flex flex-col items-center mb-2">
                        <label for="avatar-upload" class="cursor-pointer group">
                            <div class="relative">
                                <div id="avatar-circle" class="w-24 h-24 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-3xl font-bold overflow-hidden border-4 border-white shadow-md">
                                    @if($member && $member->avatar)
                                        <img id="avatar-img" src="{{ Storage::url($member->avatar) }}" class="avatar-preview w-full h-full">
                                    @else
                                        <span id="avatar-initials">{{ strtoupper(substr($member->first_name ?? auth()->user()->email, 0, 1)) }}</span>
                                        <img id="avatar-img" class="avatar-preview w-full h-full hidden">
                                    @endif
                                </div>
                                <div class="absolute bottom-0 right-0 w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center shadow-md group-hover:bg-blue-700 transition-colors">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                            </div>
                        </label>
                        <input type="file" id="avatar-upload" name="avatar" accept="image/*" class="hidden" onchange="previewAvatar(event)">
                        <p class="text-xs text-gray-400 mt-2">Click to upload photo (optional)</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">First Name *</label>
                            <input type="text" name="first_name" value="{{ old('first_name', $member->first_name) }}" required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('first_name') border-red-400 @enderror">
                            @error('first_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Last Name *</label>
                            <input type="text" name="last_name" value="{{ old('last_name', $member->last_name) }}" required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('last_name') border-red-400 @enderror">
                            @error('last_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Phone <span class="text-gray-400 font-normal">(optional)</span></label>
                        <input type="text" name="phone" value="{{ old('phone', $member->phone) }}"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-colors shadow-sm mt-2">
                        Continue →
                    </button>
                </form>
            </div>


            {{-- ===== STEP 2: INTERESTS ===== --}}
            @elseif($step === 2)
            <div class="text-center mb-8">
                <div class="text-4xl mb-3">📚</div>
                <h1 class="text-2xl font-extrabold text-gray-900">What do you like to read?</h1>
                <p class="text-gray-500 mt-2">Select at least one category to personalize your library.</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                <form action="{{ route('member.onboarding.step2') }}" method="POST" id="interests-form">
                    @csrf

                    @error('preferred_categories')
                        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-600">
                            {{ $message }}
                        </div>
                    @enderror

                    {{-- Category Grid --}}
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-6" id="category-grid">
                        @foreach($categories as $cat)
                        @php
                            $icons = ['fiction'=>'📖','non-fiction'=>'📰','science'=>'🔬','technology'=>'💻','history'=>'🏛️','romance'=>'💕','mystery'=>'🔍','biography'=>'👤','fantasy'=>'🧙','horror'=>'👻','adventure'=>'🗺️','art'=>'🎨','business'=>'💼','health'=>'🏥','sports'=>'⚽','cooking'=>'🍳','travel'=>'✈️','religion'=>'🙏'];
                            $slug = \Illuminate\Support\Str::slug($cat->name);
                            $icon = $icons[$slug] ?? '📚';
                            $isSelected = is_array($member?->preferred_categories) && in_array($cat->id, $member->preferred_categories ?? []);
                        @endphp
                        <label class="category-card {{ $isSelected ? 'selected' : '' }} cursor-pointer border-2 border-gray-200 rounded-xl p-4 text-center hover:border-blue-300 transition-all duration-150 relative select-none" data-id="{{ $cat->id }}">
                            <input type="checkbox" name="preferred_categories[]" value="{{ $cat->id }}"
                                {{ $isSelected ? 'checked' : '' }}
                                class="hidden category-checkbox">
                            <div class="absolute top-2 right-2 check-icon w-5 h-5 bg-blue-600 rounded-full items-center justify-center">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div class="cat-icon text-2xl mb-1.5 transition-colors">{{ $icon }}</div>
                            <div class="text-xs font-semibold text-gray-700 leading-tight">{{ $cat->name }}</div>
                        </label>
                        @endforeach
                    </div>

                    <p id="selection-hint" class="text-xs text-center text-gray-400 mb-4" style="display:none;">Select at least 1 category to continue.</p>

                    <button type="submit" id="continue-btn" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-colors shadow-sm">
                        Continue →
                    </button>
                </form>
            </div>


            {{-- ===== STEP 3: DONE ===== --}}
            @elseif($step === 3)
            <div class="text-center mb-8">
                <div class="text-5xl mb-4 animate-bounce">🎉</div>
                <h1 class="text-3xl font-extrabold text-gray-900">You're all set!</h1>
                <p class="text-gray-500 mt-2">Welcome to ELibrary, <strong>{{ $member->first_name ?: 'Reader' }}</strong>. Your personalized library is ready.</p>
            </div>

            {{-- Summary --}}
            @if($member && !empty($member->preferred_categories))
            <div class="bg-blue-50 border border-blue-100 rounded-2xl p-5 mb-6">
                <p class="text-sm font-semibold text-blue-800 mb-3">📌 Your interests:</p>
                <div class="flex flex-wrap gap-2">
                    @foreach(\App\Models\Category::whereIn('id', $member->preferred_categories)->get() as $cat)
                        <span class="px-3 py-1 bg-white border border-blue-200 text-blue-700 text-xs font-semibold rounded-full shadow-sm">{{ $cat->name }}</span>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Recommended Ebooks --}}
            @if($recommendedEbooks->isNotEmpty())
            <div class="mb-6">
                <h2 class="font-bold text-gray-900 mb-3 text-sm uppercase tracking-wider text-center">Recommended for You</h2>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    @foreach($recommendedEbooks as $ebook)
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden text-center p-3">
                        @if($ebook->cover_image)
                            <img src="{{ Storage::url($ebook->cover_image) }}" class="w-full h-24 object-cover rounded-lg mb-2">
                        @else
                            <div class="w-full h-24 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg mb-2 flex items-center justify-center">
                                <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            </div>
                        @endif
                        <p class="text-xs font-semibold text-gray-800 truncate">{{ $ebook->title }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ $ebook->authors->pluck('full_name')->join(', ') }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <form action="{{ route('member.onboarding.step3') }}" method="POST">
                @csrf
                <button type="submit" class="w-full py-3.5 bg-gray-900 hover:bg-black text-white font-bold rounded-xl transition-colors shadow-sm text-base">
                    Start Exploring Books 📖
                </button>
            </form>

            @endif

        </div>
    </main>

    <script>
        // ── Avatar Preview ──────────────────────────────────────────────────────
        function previewAvatar(event) {
            const file = event.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('avatar-img');
                const initials = document.getElementById('avatar-initials');
                img.src = e.target.result;
                img.classList.remove('hidden');
                if (initials) initials.style.display = 'none';
            };
            reader.readAsDataURL(file);
        }

        // ── Category Card Toggle ────────────────────────────────────────────────
        document.querySelectorAll('.category-card').forEach(card => {
            card.addEventListener('click', function() {
                const checkbox = this.querySelector('.category-checkbox');
                checkbox.checked = !checkbox.checked;
                this.classList.toggle('selected', checkbox.checked);
            });
        });

        // ── Form validation: require at least 1 category ────────────────────────
        const form = document.getElementById('interests-form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const checked = form.querySelectorAll('.category-checkbox:checked');
                if (checked.length === 0) {
                    e.preventDefault();
                    document.getElementById('selection-hint').style.display = 'block';
                }
            });
        }
    </script>
</body>
</html>
