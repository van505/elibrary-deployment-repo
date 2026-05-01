@extends('layouts.member')
@section('title', 'Help & Support')

@push('breadcrumbs')
<nav class="flex items-center text-sm">
    <a href="{{ route('member.dashboard') }}" class="text-gray-400 hover:text-gray-600 transition-colors duration-150">Home</a>
    <span class="text-gray-300 mx-1.5 select-none">›</span>
    <span class="text-gray-700 font-medium">Help &amp; Support</span>
</nav>
@endpush

@section('content')
<div class="max-w-4xl mx-auto space-y-10">

    {{-- ══════════════════════════════════════════════ --}}
    {{-- PAGE HEADER                                    --}}
    {{-- ══════════════════════════════════════════════ --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Help &amp; Support</h1>
        <p class="text-sm text-gray-500 mt-1">Find answers, guides, and ways to get in touch.</p>
    </div>

    {{-- ══════════════════════════════════════════════ --}}
    {{-- SECTION 1: HOW IT WORKS                        --}}
    {{-- ══════════════════════════════════════════════ --}}
    <div>
        <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100">How It Works</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- Step 1 --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-[0_4px_6px_-1px_rgba(0,0,0,0.07)] p-6 text-center hover:shadow-[0_8px_15px_-3px_rgba(0,0,0,0.08)] hover:-translate-y-0.5 transition-all duration-200">
                <div class="w-7 h-7 rounded-full bg-indigo-600 text-white text-xs font-bold flex items-center justify-center mx-auto mb-3">1</div>
                <div class="flex items-center justify-center mb-3">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-800 mb-2">Browse the Library</h3>
                <p class="text-sm text-gray-500 leading-relaxed">Explore thousands of ebooks across all genres and categories. Use filters to find exactly what you are looking for.</p>
            </div>

            {{-- Step 2 --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-[0_4px_6px_-1px_rgba(0,0,0,0.07)] p-6 text-center hover:shadow-[0_8px_15px_-3px_rgba(0,0,0,0.08)] hover:-translate-y-0.5 transition-all duration-200">
                <div class="w-7 h-7 rounded-full bg-indigo-600 text-white text-xs font-bold flex items-center justify-center mx-auto mb-3">2</div>
                <div class="flex items-center justify-center mb-3">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-800 mb-2">Subscribe to a Plan</h3>
                <p class="text-sm text-gray-500 leading-relaxed">Pick a plan that fits your reading needs. Free, Basic, and Premium plans are available with different ebook access limits.</p>
            </div>

            {{-- Step 3 --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-[0_4px_6px_-1px_rgba(0,0,0,0.07)] p-6 text-center hover:shadow-[0_8px_15px_-3px_rgba(0,0,0,0.08)] hover:-translate-y-0.5 transition-all duration-200">
                <div class="w-7 h-7 rounded-full bg-indigo-600 text-white text-xs font-bold flex items-center justify-center mx-auto mb-3">3</div>
                <div class="flex items-center justify-center mb-3">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-800 mb-2">Read Anywhere</h3>
                <p class="text-sm text-gray-500 leading-relaxed">Access your ebooks instantly from any device. Track your reading history and pick up where you left off.</p>
            </div>

        </div>
    </div>

    {{-- ══════════════════════════════════════════════ --}}
    {{-- SECTION 2: FAQ ACCORDION                       --}}
    {{-- ══════════════════════════════════════════════ --}}
    <div>
        <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100">Frequently Asked Questions</h2>

        <div class="space-y-2">

            {{-- FAQ 1 --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden" x-data="{ open: false }">
                <button @click="open = !open" class="w-full flex items-center justify-between px-5 py-4 hover:bg-gray-50 transition-colors duration-150">
                    <span class="text-sm font-medium text-gray-900 text-left">How do I borrow or access an ebook?</span>
                    <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 text-gray-400 transition-transform duration-200 flex-shrink-0 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="px-5 pb-4 text-sm text-gray-600 leading-relaxed border-t border-gray-50">
                    Browse the library, click on any ebook, and press "Read Now". If the ebook requires a paid plan, upgrade your subscription first from <strong>My Subscription</strong> in the sidebar.
                </div>
            </div>

            {{-- FAQ 2 --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden" x-data="{ open: false }">
                <button @click="open = !open" class="w-full flex items-center justify-between px-5 py-4 hover:bg-gray-50 transition-colors duration-150">
                    <span class="text-sm font-medium text-gray-900 text-left">What is the difference between the plans?</span>
                    <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 text-gray-400 transition-transform duration-200 flex-shrink-0 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="px-5 pb-4 text-sm text-gray-600 leading-relaxed border-t border-gray-50">
                    The <strong>Free</strong> plan gives you access to 3 ebooks. The <strong>Basic</strong> plan gives access to 10 ebooks at ₱99/month. The <strong>Premium</strong> plan gives unlimited access at ₱199/month.
                </div>
            </div>

            {{-- FAQ 3 --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden" x-data="{ open: false }">
                <button @click="open = !open" class="w-full flex items-center justify-between px-5 py-4 hover:bg-gray-50 transition-colors duration-150">
                    <span class="text-sm font-medium text-gray-900 text-left">How do I track my reading progress?</span>
                    <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 text-gray-400 transition-transform duration-200 flex-shrink-0 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="px-5 pb-4 text-sm text-gray-600 leading-relaxed border-t border-gray-50">
                    Your reading history is automatically saved. Visit <strong>Reading History</strong> in the sidebar to see all ebooks you have accessed, along with your progress and last read date.
                </div>
            </div>

            {{-- FAQ 4 --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden" x-data="{ open: false }">
                <button @click="open = !open" class="w-full flex items-center justify-between px-5 py-4 hover:bg-gray-50 transition-colors duration-150">
                    <span class="text-sm font-medium text-gray-900 text-left">Can I bookmark ebooks for later?</span>
                    <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 text-gray-400 transition-transform duration-200 flex-shrink-0 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="px-5 pb-4 text-sm text-gray-600 leading-relaxed border-t border-gray-50">
                    Yes. Click the star or bookmark icon on any ebook card or detail page. Find all your saved ebooks in <strong>My Bookmarks</strong> in the sidebar.
                </div>
            </div>

            {{-- FAQ 5 --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden" x-data="{ open: false }">
                <button @click="open = !open" class="w-full flex items-center justify-between px-5 py-4 hover:bg-gray-50 transition-colors duration-150">
                    <span class="text-sm font-medium text-gray-900 text-left">How do I cancel my subscription?</span>
                    <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 text-gray-400 transition-transform duration-200 flex-shrink-0 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="px-5 pb-4 text-sm text-gray-600 leading-relaxed border-t border-gray-50">
                    Go to <strong>My Subscription</strong> in the sidebar and click Manage Subscription. You can cancel or downgrade your plan from there. Your access continues until the end of your current billing period.
                </div>
            </div>

            {{-- FAQ 6 --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden" x-data="{ open: false }">
                <button @click="open = !open" class="w-full flex items-center justify-between px-5 py-4 hover:bg-gray-50 transition-colors duration-150">
                    <span class="text-sm font-medium text-gray-900 text-left">How do I change my password?</span>
                    <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 text-gray-400 transition-transform duration-200 flex-shrink-0 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="px-5 pb-4 text-sm text-gray-600 leading-relaxed border-t border-gray-50">
                    Click your avatar in the top right corner, select <strong>Security &amp; Settings</strong>, and use the Change Password form on that page.
                </div>
            </div>

            {{-- FAQ 7 --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden" x-data="{ open: false }">
                <button @click="open = !open" class="w-full flex items-center justify-between px-5 py-4 hover:bg-gray-50 transition-colors duration-150">
                    <span class="text-sm font-medium text-gray-900 text-left">Why can I not access certain ebooks?</span>
                    <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 text-gray-400 transition-transform duration-200 flex-shrink-0 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="px-5 pb-4 text-sm text-gray-600 leading-relaxed border-t border-gray-50">
                    Some ebooks require a Basic or Premium plan. If you see a lock icon or a plan badge on an ebook, upgrade your subscription to access it. Free plan members can only access ebooks marked as <strong>Free</strong>.
                </div>
            </div>

            {{-- FAQ 8 --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden" x-data="{ open: false }">
                <button @click="open = !open" class="w-full flex items-center justify-between px-5 py-4 hover:bg-gray-50 transition-colors duration-150">
                    <span class="text-sm font-medium text-gray-900 text-left">How do I write a review?</span>
                    <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 text-gray-400 transition-transform duration-200 flex-shrink-0 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="px-5 pb-4 text-sm text-gray-600 leading-relaxed border-t border-gray-50">
                    Go to <strong>My Reviews</strong> in the sidebar. You can submit a star rating and written review for any ebook you have accessed. Reviews are submitted for admin approval before being published.
                </div>
            </div>

        </div>
    </div>

    {{-- ══════════════════════════════════════════════ --}}
    {{-- SECTION 3: CONTACT SUPPORT                     --}}
    {{-- ══════════════════════════════════════════════ --}}
    <div>
        <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100">Contact Support</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Email Support --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-[0_4px_6px_-1px_rgba(0,0,0,0.07)] p-6 hover:shadow-[0_8px_15px_-3px_rgba(0,0,0,0.08)] hover:-translate-y-0.5 transition-all duration-200">
                <div class="w-10 h-10 bg-indigo-600 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-800 mb-1">Email Support</h3>
                <p class="text-sm text-gray-500 mb-4 leading-relaxed">Send us a message and we will get back to you within 24 hours.</p>
                <a href="mailto:support@elibrary.com"
                   class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg px-4 py-2 text-sm font-medium transition-colors duration-150">
                    Send an Email
                </a>
            </div>

            {{-- Help Center --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-[0_4px_6px_-1px_rgba(0,0,0,0.07)] p-6 hover:shadow-[0_8px_15px_-3px_rgba(0,0,0,0.08)] hover:-translate-y-0.5 transition-all duration-200">
                <div class="w-10 h-10 bg-teal-500 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-800 mb-1">Browse Documentation</h3>
                <p class="text-sm text-gray-500 mb-4 leading-relaxed">Check our detailed guides for step-by-step instructions on using every feature.</p>
                <a href="#"
                   class="inline-block border border-gray-200 hover:bg-gray-50 text-gray-700 rounded-lg px-4 py-2 text-sm font-medium transition-colors duration-150">
                    View Guides
                </a>
            </div>

        </div>
    </div>

    {{-- ══════════════════════════════════════════════ --}}
    {{-- SECTION 4: QUICK LINKS                         --}}
    {{-- ══════════════════════════════════════════════ --}}
    <div class="text-center text-sm text-gray-400 pb-4">
        <a href="#" class="hover:text-gray-600 transition-colors duration-150">Privacy Policy</a>
        <span class="mx-2">·</span>
        <a href="#" class="hover:text-gray-600 transition-colors duration-150">Terms of Service</a>
        <span class="mx-2">·</span>
        <a href="{{ route('member.dashboard') }}" class="hover:text-gray-600 transition-colors duration-150">Back to Dashboard</a>
    </div>

</div>
@endsection
