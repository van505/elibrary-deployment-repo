@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="space-y-4">

    {{-- ── Dashboard Header ─────────────────────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-900 via-indigo-900 to-purple-900 p-8 shadow-lg border border-blue-800/50">
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 mix-blend-overlay"></div>
        <div class="relative z-10">
            <h1 class="text-2xl md:text-3xl font-extrabold text-white tracking-tight drop-shadow-md">Welcome back, {{ auth()->user()->first_name ?: auth()->user()->email }} 👋</h1>
            <p class="text-blue-200 mt-1.5 font-medium flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                System is running smoothly. Here's your overview.
            </p>
        </div>
        <div class="relative z-10 hidden sm:block">
            <button id="open-customize-btn"
                onclick="openCustomizePanel()"
                class="flex items-center gap-2 bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/20 text-white text-sm font-semibold px-5 py-2.5 rounded-xl shadow-xl hover:shadow-2xl hover:-translate-y-0.5 transition-all duration-300">
                <svg class="w-4 h-4 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                Customize Layout (<span id="visible-count">12</span>/12)
            </button>
        </div>
    </div>

    {{-- ── Alerts (non-widget, always visible) ─────────────────────────────── --}}
    @if($pendingReviews > 0)
    <div class="bg-gradient-to-r from-amber-50 to-amber-100/50 border border-amber-200/60 rounded-2xl p-4 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 bg-gradient-to-br from-amber-400 to-orange-500 rounded-xl flex items-center justify-center shadow-inner text-white">
                <svg class="w-5 h-5 drop-shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-amber-900 tracking-tight">Action Required</p>
                <p class="text-sm font-medium text-amber-700 mt-0.5">
                    <span class="font-bold text-amber-900">{{ $pendingReviews }}</span> review{{ $pendingReviews > 1 ? 's' : '' }} awaiting your approval
                </p>
            </div>
        </div>
        <a href="{{ route('admin.reviews.index', ['status' => 'pending']) }}" class="flex items-center gap-1.5 bg-white/80 hover:bg-white text-amber-900 text-sm font-bold px-4 py-2 rounded-lg border border-amber-200 shadow-sm transition-all hover:scale-105">
            Review Now <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>
    @endif

    @if(isset($activeAnnouncements) && $activeAnnouncements->count() > 0)
    <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-gray-800 font-extrabold text-sm uppercase tracking-wider flex items-center gap-2">
                <div class="w-2 h-2 rounded-full bg-blue-500 shadow-[0_0_8px_rgba(59,130,246,0.8)]"></div> Active Announcements
            </h3>
            <a href="{{ route('admin.announcements.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition-colors flex items-center gap-1 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg border border-blue-100">
                Manage Announcements
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($activeAnnouncements as $ann)
                @php $bgGradients = ['info'=>'from-blue-50 to-indigo-50/30 border-blue-200 text-blue-900','warning'=>'from-amber-50 to-yellow-50/30 border-amber-200 text-amber-900','success'=>'from-emerald-50 to-green-50/30 border-emerald-200 text-emerald-900','danger'=>'from-red-50 to-rose-50/30 border-red-200 text-red-900']; @endphp
                <div class="bg-gradient-to-br {{ $bgGradients[$ann->type] ?? 'from-gray-50 to-white border-gray-200' }} px-4 py-3 rounded-xl border shadow-sm flex flex-col justify-between hover:-translate-y-0.5 transition-transform hover:shadow-md cursor-default">
                    <div class="text-[10px] font-black uppercase mb-1 opacity-60 tracking-widest">{{ $ann->type }}</div>
                    <div class="font-bold text-sm leading-tight">{{ $ann->title }}</div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ════════════════════════════════════════════════════════════════════════
         WIDGET CONTAINER — all 12 widgets live here
         Uses CSS flexbox with `order` for JS reordering
    ════════════════════════════════════════════════════════════════════════ --}}
    <div id="dashboard-widgets-container" class="flex flex-wrap gap-5">

        {{-- ── [1] WIDGET: total_members ──────────────────────────────────────── --}}
        <div id="widget-total_members" data-widget-id="total_members"
             class="dashboard-widget stat-widget bg-white hover:bg-gradient-to-b hover:from-white hover:to-blue-50/30 rounded-2xl shadow-sm hover:shadow-xl border border-gray-100 p-6 transition-all duration-300 hover:-translate-y-1 group relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-blue-50 to-transparent rounded-bl-full opacity-50 pointer-events-none group-hover:from-blue-100/50 transition-colors"></div>
            <div class="widget-header relative z-10">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200/50 rounded-xl flex items-center justify-center border border-blue-200/30 shadow-inner group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-blue-600 drop-shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div class="widget-controls flex items-center gap-2">
                    <span class="text-xs bg-green-50 text-green-600 border border-green-100 px-2.5 py-1 rounded-full font-bold shadow-sm translate-x-4 opacity-0 group-hover:translate-x-0 group-hover:opacity-100 transition-all duration-300">+{{ $newMembersThisMonth }} mo.</span>
                    <button class="widget-hide-btn" onclick="hideWidget('total_members')" title="Hide widget">✕</button>
                </div>
            </div>
            <p class="text-4xl font-extrabold text-gray-800 mt-5 tracking-tight relative z-10">{{ $totalMembers }}</p>
            <p class="text-sm font-semibold text-gray-400 mt-1 uppercase tracking-wider relative z-10">Total Members</p>
        </div>

        {{-- ── [2] WIDGET: new_members_month ──────────────────────────────────── --}}
        <div id="widget-new_members_month" data-widget-id="new_members_month"
             class="dashboard-widget stat-widget bg-white hover:bg-gradient-to-b hover:from-white hover:to-teal-50/30 rounded-2xl shadow-sm hover:shadow-xl border border-gray-100 p-6 transition-all duration-300 hover:-translate-y-1 group relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-teal-50 to-transparent rounded-bl-full opacity-50 pointer-events-none group-hover:from-teal-100/50 transition-colors"></div>
            <div class="widget-header relative z-10">
                <div class="w-12 h-12 bg-gradient-to-br from-teal-100 to-teal-200/50 rounded-xl flex items-center justify-center border border-teal-200/30 shadow-inner group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-teal-600 drop-shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <div class="widget-controls">
                    <span class="text-[10px] font-black uppercase text-gray-400 tracking-wider bg-gray-50 border border-gray-100 px-2 py-1 rounded-md">{{ now()->format('M Y') }}</span>
                    <button class="widget-hide-btn" onclick="hideWidget('new_members_month')" title="Hide widget">✕</button>
                </div>
            </div>
            <p class="text-4xl font-extrabold text-gray-800 mt-5 tracking-tight relative z-10">{{ $newMembersThisMonth }}</p>
            <p class="text-sm font-semibold text-gray-400 mt-1 uppercase tracking-wider relative z-10">New This Mo.</p>
        </div>

        {{-- ── [3] WIDGET: total_ebooks ────────────────────────────────────────── --}}
        <div id="widget-total_ebooks" data-widget-id="total_ebooks"
             class="dashboard-widget stat-widget bg-white hover:bg-gradient-to-b hover:from-white hover:to-indigo-50/30 rounded-2xl shadow-sm hover:shadow-xl border border-gray-100 p-6 transition-all duration-300 hover:-translate-y-1 group relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-indigo-50 to-transparent rounded-bl-full opacity-50 pointer-events-none group-hover:from-indigo-100/50 transition-colors"></div>
            <div class="widget-header relative z-10">
                <div class="w-12 h-12 bg-gradient-to-br from-indigo-100 to-indigo-200/50 rounded-xl flex items-center justify-center border border-indigo-200/30 shadow-inner group-hover:-rotate-6 transition-transform">
                    <svg class="w-6 h-6 text-indigo-600 drop-shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <div class="widget-controls">
                    <button class="widget-hide-btn" onclick="hideWidget('total_ebooks')" title="Hide widget">✕</button>
                </div>
            </div>
            <p class="text-4xl font-extrabold text-gray-800 mt-5 tracking-tight relative z-10">{{ $totalEbooks }}</p>
            <p class="text-sm font-semibold text-gray-400 mt-1 uppercase tracking-wider relative z-10">Total Ebooks</p>
        </div>

        {{-- ── [4] WIDGET: total_authors ───────────────────────────────────────── --}}
        <div id="widget-total_authors" data-widget-id="total_authors"
             class="dashboard-widget stat-widget bg-white hover:bg-gradient-to-b hover:from-white hover:to-purple-50/30 rounded-2xl shadow-sm hover:shadow-xl border border-gray-100 p-6 transition-all duration-300 hover:-translate-y-1 group relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-purple-50 to-transparent rounded-bl-full opacity-50 pointer-events-none group-hover:from-purple-100/50 transition-colors"></div>
            <div class="widget-header relative z-10">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-100 to-purple-200/50 rounded-xl flex items-center justify-center border border-purple-200/30 shadow-inner group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-purple-600 drop-shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <div class="widget-controls">
                    <button class="widget-hide-btn" onclick="hideWidget('total_authors')" title="Hide widget">✕</button>
                </div>
            </div>
            <p class="text-4xl font-extrabold text-gray-800 mt-5 tracking-tight relative z-10">{{ $totalAuthors }}</p>
            <p class="text-sm font-semibold text-gray-400 mt-1 uppercase tracking-wider relative z-10">Total Authors</p>
        </div>

        {{-- ── [5] WIDGET: total_categories ────────────────────────────────────── --}}
        <div id="widget-total_categories" data-widget-id="total_categories"
             class="dashboard-widget stat-widget bg-white hover:bg-gradient-to-b hover:from-white hover:to-violet-50/30 rounded-2xl shadow-sm hover:shadow-xl border border-gray-100 p-6 transition-all duration-300 hover:-translate-y-1 group relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-violet-50 to-transparent rounded-bl-full opacity-50 pointer-events-none group-hover:from-violet-100/50 transition-colors"></div>
            <div class="widget-header relative z-10">
                <div class="w-12 h-12 bg-gradient-to-br from-violet-100 to-violet-200/50 rounded-xl flex items-center justify-center border border-violet-200/30 shadow-inner group-hover:-rotate-6 transition-transform">
                    <svg class="w-6 h-6 text-violet-600 drop-shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                </div>
                <div class="widget-controls">
                    <button class="widget-hide-btn" onclick="hideWidget('total_categories')" title="Hide widget">✕</button>
                </div>
            </div>
            <p class="text-4xl font-extrabold text-gray-800 mt-5 tracking-tight relative z-10">{{ $totalCategories }}</p>
            <p class="text-sm font-semibold text-gray-400 mt-1 uppercase tracking-wider relative z-10">Categories</p>
        </div>

        {{-- ── [6] WIDGET: active_subscriptions ───────────────────────────────── --}}
        <div id="widget-active_subscriptions" data-widget-id="active_subscriptions"
             class="dashboard-widget stat-widget bg-white hover:bg-gradient-to-b hover:from-white hover:to-green-50/30 rounded-2xl shadow-sm hover:shadow-xl border border-gray-100 p-6 transition-all duration-300 hover:-translate-y-1 group relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-green-50 to-transparent rounded-bl-full opacity-50 pointer-events-none group-hover:from-green-100/50 transition-colors"></div>
            <div class="widget-header relative z-10">
                <div class="w-12 h-12 bg-gradient-to-br from-green-100 to-green-200/50 rounded-xl flex items-center justify-center border border-green-200/30 shadow-inner group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-green-600 drop-shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                </div>
                <div class="widget-controls">
                    <button class="widget-hide-btn" onclick="hideWidget('active_subscriptions')" title="Hide widget">✕</button>
                </div>
            </div>
            <p class="text-4xl font-extrabold text-gray-800 mt-5 tracking-tight relative z-10">{{ $activeSubscriptions }}</p>
            <p class="text-sm font-semibold text-gray-400 mt-1 uppercase tracking-wider relative z-10">Active Subs</p>
        </div>

        {{-- ── [7] WIDGET: revenue_month ───────────────────────────────────────── --}}
        <div id="widget-revenue_month" data-widget-id="revenue_month"
             class="dashboard-widget stat-widget bg-white hover:bg-gradient-to-b hover:from-white hover:to-emerald-50/30 rounded-2xl shadow-sm hover:shadow-xl border border-gray-100 p-6 transition-all duration-300 hover:-translate-y-1 group relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-emerald-50 to-transparent rounded-bl-full opacity-50 pointer-events-none group-hover:from-emerald-100/50 transition-colors"></div>
            <div class="widget-header relative z-10">
                <div class="w-12 h-12 bg-gradient-to-br from-emerald-400 to-emerald-500 rounded-xl flex items-center justify-center border border-emerald-500/30 shadow-inner shadow-emerald-700/20 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white drop-shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <div class="widget-controls">
                    <span class="text-[10px] font-black uppercase text-emerald-600 tracking-wider bg-emerald-50 border border-emerald-100 px-2 py-1 rounded-md">This Month</span>
                    <button class="widget-hide-btn" onclick="hideWidget('revenue_month')" title="Hide widget">✕</button>
                </div>
            </div>
            <p class="text-3xl lg:text-4xl font-extrabold text-gray-800 mt-5 tracking-tight relative z-10 drop-shadow-sm">₱{{ number_format($revenueThisMonth, 2) }}</p>
            <p class="text-sm font-semibold text-gray-400 mt-1 uppercase tracking-wider relative z-10">Revenue</p>
        </div>

        {{-- ── [8] WIDGET: pending_reviews ─────────────────────────────────────── --}}
        <div id="widget-pending_reviews" data-widget-id="pending_reviews"
             class="dashboard-widget stat-widget bg-white hover:bg-gradient-to-b hover:from-white hover:to-amber-50/30 rounded-2xl shadow-sm hover:shadow-xl border border-gray-100 p-6 transition-all duration-300 hover:-translate-y-1 group relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-amber-50 to-transparent rounded-bl-full opacity-50 pointer-events-none group-hover:from-amber-100/50 transition-colors"></div>
            <div class="widget-header relative z-10">
                <div class="w-12 h-12 bg-gradient-to-br from-amber-100 to-amber-200/50 rounded-xl flex items-center justify-center border border-amber-200/30 shadow-inner group-hover:-rotate-6 transition-transform">
                    <svg class="w-6 h-6 text-amber-600 drop-shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                </div>
                <div class="widget-controls">
                    @if($pendingReviews > 0)
                    <span class="bg-red-500 text-white shadow shadow-red-500/40 text-[10px] font-bold px-2 py-1 rounded-md tracking-wider uppercase animate-pulse">{{ $pendingReviews }} Pending</span>
                    @endif
                    <button class="widget-hide-btn" onclick="hideWidget('pending_reviews')" title="Hide widget">✕</button>
                </div>
            </div>
            <p class="text-4xl font-extrabold text-gray-800 mt-5 tracking-tight relative z-10">{{ $pendingReviews }}</p>
            <p class="text-sm font-semibold text-gray-400 mt-1 uppercase tracking-wider relative z-10">Review Queue</p>
        </div>

        {{-- ── [9] WIDGET: activity_feed ───────────────────────────────────────── --}}
        <div id="widget-activity_feed" data-widget-id="activity_feed"
             class="dashboard-widget full-widget bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-shadow">
            <div class="px-6 py-5 border-b border-gray-100/80 flex items-center justify-between bg-gray-50/50">
                <div class="flex items-center gap-3">
                    <span class="relative flex h-3 w-3">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-3 w-3 bg-blue-500"></span>
                    </span>
                    <h2 class="font-bold text-gray-800 tracking-tight">Live Activity Feed</h2>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.activity-logs.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 hover:underline transition-all">View all →</a>
                    <button class="widget-hide-btn bg-white shadow-sm border border-gray-200" onclick="hideWidget('activity_feed')" title="Hide widget">✕</button>
                </div>
            </div>
            <div class="divide-y divide-gray-50 max-h-96 overflow-y-auto">
                @forelse($activityFeed as $log)
                <div class="px-6 py-4 flex items-start gap-4 hover:bg-blue-50/30 transition-colors group">
                    <div class="w-10 h-10 bg-gray-50 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5 border border-gray-100 group-hover:bg-blue-100 group-hover:text-blue-600 group-hover:border-blue-200 transition-colors">
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-900 font-bold tracking-tight truncate">{{ $log->action }}</p>
                        <p class="text-xs text-gray-500 truncate mt-0.5">{{ Str::limit($log->description, 90) }}</p>
                        <p class="text-[11px] font-semibold text-gray-400 mt-1 uppercase tracking-wider flex gap-2">
                            <span class="text-blue-600">{{ $log->user?->email ?? 'System' }}</span> &bull; <span>{{ $log->created_at?->diffForHumans() }}</span>
                        </p>
                    </div>
                    <span class="bg-gray-100 text-gray-500 border border-gray-200 px-2 py-1 rounded text-[10px] font-bold uppercase tracking-widest">{{ $log->module }}</span>
                </div>
                @empty
                <div class="px-6 py-12 text-center text-gray-400 text-sm flex flex-col items-center gap-2">
                    <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                    No recent activity.
                </div>
                @endforelse
            </div>
        </div>

        {{-- ── [10] WIDGET: recent_transactions ────────────────────────────────── --}}
        <div id="widget-recent_transactions" data-widget-id="recent_transactions"
             class="dashboard-widget full-widget bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-shadow">
            <div class="px-6 py-5 border-b border-gray-100/80 flex items-center justify-between bg-gray-50/50">
                <h2 class="font-bold text-gray-800 tracking-tight flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    Recent Transactions
                </h2>
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.transactions.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 hover:underline transition-all">View all →</a>
                    <button class="widget-hide-btn bg-white shadow-sm border border-gray-200" onclick="hideWidget('recent_transactions')" title="Hide widget">✕</button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50/50 text-gray-400 text-[10px] font-black uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Member</th>
                            <th class="px-6 py-4">Plan Name</th>
                            <th class="px-6 py-4">Amount</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($recentTransactions as $tx)
                        <tr class="hover:bg-gray-50/80 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800">{{ $tx->member?->full_name ?: '—' }}</div>
                                <div class="text-xs text-gray-400">{{ $tx->member?->email }}</div>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-600">{{ $tx->plan?->name ?: '—' }}</td>
                            <td class="px-6 py-4 font-bold text-gray-900 group-hover:text-emerald-600 transition-colors">₱{{ number_format($tx->amount, 2) }}</td>
                            <td class="px-6 py-4">
                                @php $txColors = ['completed'=>'bg-emerald-50 text-emerald-700 border-emerald-200','pending'=>'bg-amber-50 text-amber-700 border-amber-200','failed'=>'bg-rose-50 text-rose-700 border-rose-200']; @endphp
                                <span class="px-2.5 py-1 rounded-md border text-[11px] font-bold uppercase tracking-wider {{ $txColors[$tx->status] ?? 'bg-gray-50 text-gray-600 border-gray-200' }}">
                                    {{ ucfirst($tx->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-500 text-xs font-medium">{{ $tx->created_at->format('M d, Y') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-6 py-12 text-center text-gray-400">No transactions yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ── [11] WIDGET: top_ebooks ─────────────────────────────────────────── --}}
        <div id="widget-top_ebooks" data-widget-id="top_ebooks"
             class="dashboard-widget full-widget bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-shadow">
            <div class="px-6 py-5 border-b border-gray-100/80 flex items-center justify-between bg-gray-50/50">
                <h2 class="font-bold text-gray-800 tracking-tight flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                    Most Read Ebooks Top Chart
                </h2>
                <button class="widget-hide-btn bg-white shadow-sm border border-gray-200" onclick="hideWidget('top_ebooks')" title="Hide widget">✕</button>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($topEbooks as $i => $ebook)
                <div class="px-6 py-4 flex items-center gap-5 hover:bg-gray-50/80 transition-colors group">
                    <div class="text-xl font-black {{ $i < 3 ? 'text-gray-800' : 'text-gray-300' }} w-8 text-center tabular-nums">{{ $i + 1 }}</div>
                    <div class="w-12 h-16 bg-gray-100 rounded border border-gray-200 flex items-center justify-center overflow-hidden flex-shrink-0 shadow-sm group-hover:shadow transition-shadow">
                        @if($ebook->cover_image)
                            <img src="{{ Storage::url($ebook->cover_image) }}" alt="Cover" class="w-full h-full object-cover">
                        @else
                            <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-gray-900 truncate">{{ $ebook->title }}</p>
                        <p class="text-xs text-gray-500 truncate mt-0.5">{{ $ebook->authors->pluck('full_name')->join(', ') ?: 'Unknown' }}</p>
                    </div>
                    <div class="text-right flex-shrink-0 bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-100 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <p class="text-sm font-black text-blue-600 group-hover:text-white leading-none">{{ $ebook->ebook_access_count }}</p>
                        <p class="text-[10px] font-bold text-blue-400 group-hover:text-blue-200 uppercase tracking-widest mt-0.5">Reads</p>
                    </div>
                </div>
                @empty
                <div class="px-6 py-12 text-center text-gray-400 text-sm">No reading data yet.</div>
                @endforelse
            </div>
        </div>

        {{-- ── [12] WIDGET: subscription_chart ─────────────────────────────────── --}}
        <div id="widget-subscription_chart" data-widget-id="subscription_chart"
             class="dashboard-widget full-widget bg-white rounded-2xl shadow-sm border border-gray-100 p-8 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="font-bold text-gray-800 tracking-tight text-lg">Subscriptions Growth</h2>
                    <p class="text-sm text-gray-400 mt-1 font-medium">New premium tier subscriptions over the last 6 months</p>
                </div>
                <button class="widget-hide-btn bg-gray-50 shadow-sm border border-gray-200" onclick="hideWidget('subscription_chart')" title="Hide widget">✕</button>
            </div>
            <div class="flex items-end gap-4 h-40">
                @php $maxCount = max($subscriptionChart->pluck('count')->max(), 1); @endphp
                @foreach($subscriptionChart as $point)
                @php 
                    $barHeight = max(4, round(($point['count'] / $maxCount) * 100)); 
                    $isLast = $loop->last;
                @endphp
                <div class="flex-1 flex flex-col items-center gap-2 group relative">
                    <span class="text-xs font-black {{ $isLast ? 'text-indigo-600' : 'text-gray-400' }} group-hover:-translate-y-1 transition-transform">{{ $point['count'] }}</span>
                    <div class="w-full relative overflow-hidden rounded-t-xl {{ $isLast ? 'bg-gradient-to-t from-indigo-500 to-purple-500 shadow-md shadow-indigo-500/20' : 'bg-gray-100 hover:bg-gray-200' }} transition-colors" style="height: {{ $barHeight }}%;" title="{{ $point['count'] }} subscriptions">
                        @if($isLast) <div class="absolute inset-0 bg-white/20 w-full h-full transform -skew-y-12"></div> @endif
                    </div>
                    <span class="text-[11px] font-bold uppercase tracking-wider {{ $isLast ? 'text-indigo-600' : 'text-gray-400' }}">{{ $point['label'] }}</span>
                </div>
                @endforeach
            </div>
            <div class="mt-8 pt-6 border-t border-gray-100 flex flex-wrap items-center justify-center sm:justify-start gap-8 text-sm">
                <div class="flex flex-col"><span class="text-gray-400 text-[10px] font-black uppercase tracking-wider">Total Active</span> <span class="font-extrabold text-xl text-gray-800">{{ $activeSubscriptions }}</span></div>
                <div class="flex flex-col"><span class="text-gray-400 text-[10px] font-black uppercase tracking-wider">Total Revenue</span> <span class="font-extrabold text-xl text-emerald-600">₱{{ number_format($revenueThisMonth, 2) }}</span></div>
                <div class="flex flex-col"><span class="text-gray-400 text-[10px] font-black uppercase tracking-wider">Total Premium</span> <span class="font-extrabold text-xl text-indigo-600">{{ $premiumMembers }}</span></div>
            </div>
        </div>

        {{-- ── Quick Links (always visible, not a widget) ──────────────────────── --}}
        <div class="w-full bg-white rounded-2xl shadow-sm p-8 border border-gray-100">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <h2 class="text-lg font-bold text-gray-800 tracking-tight">Quick Actions Workspace</h2>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                <a href="{{ route('admin.ebooks.create') }}" class="group relative bg-white border border-gray-200 rounded-xl p-4 flex flex-col items-center justify-center gap-3 hover:border-blue-500 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                    <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center group-hover:scale-110 transition-transform"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg></div>
                    <span class="text-sm font-bold text-gray-700 group-hover:text-blue-700 transition-colors">Add Ebook</span>
                </a>

                <a href="{{ route('admin.reviews.index', ['status' => 'pending']) }}" class="group relative bg-white border border-gray-200 rounded-xl p-4 flex flex-col items-center justify-center gap-3 hover:border-amber-500 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                    @if($pendingReviews > 0) <span class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] font-black px-2 py-1 rounded-lg border-2 border-white shadow-sm">{{ $pendingReviews }}</span> @endif
                    <div class="w-10 h-10 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center group-hover:scale-110 transition-transform"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg></div>
                    <span class="text-sm font-bold text-gray-700 group-hover:text-amber-700 transition-colors text-center leading-tight">Review Approvals</span>
                </a>

                <a href="{{ route('admin.subscription-plans.index') }}" class="group relative bg-white border border-gray-200 rounded-xl p-4 flex flex-col items-center justify-center gap-3 hover:border-purple-500 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                    <div class="w-10 h-10 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center group-hover:scale-110 transition-transform"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg></div>
                    <span class="text-sm font-bold text-gray-700 group-hover:text-purple-700 transition-colors text-center leading-tight">Manage Plans</span>
                </a>

                <a href="{{ route('admin.subscriptions.index') }}" class="group relative bg-white border border-gray-200 rounded-xl p-4 flex flex-col items-center justify-center gap-3 hover:border-emerald-500 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                    <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                    <span class="text-sm font-bold text-gray-700 group-hover:text-emerald-700 transition-colors text-center leading-tight">Monitor Subs</span>
                </a>

                <a href="{{ route('admin.transactions.index') }}" class="group relative bg-white border border-gray-200 rounded-xl p-4 flex flex-col items-center justify-center gap-3 hover:border-teal-500 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                    <div class="w-10 h-10 rounded-full bg-teal-50 text-teal-600 flex items-center justify-center group-hover:scale-110 transition-transform"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg></div>
                    <span class="text-sm font-bold text-gray-700 group-hover:text-teal-700 transition-colors text-center leading-tight">Transactions</span>
                </a>
            </div>
        </div>

    </div>{{-- end #dashboard-widgets-container --}}
</div>{{-- end space-y-4 --}}

{{-- ════════════════════════════════════════════════════════════════════════════
     CUSTOMIZE PANEL — slide-in from right
════════════════════════════════════════════════════════════════════════════ --}}

{{-- Backdrop --}}
<div id="customize-backdrop" onclick="closeCustomizePanel()"
     class="fixed inset-0 bg-slate-900/40 backdrop-blur-md z-40 hidden opacity-0 transition-opacity duration-300"></div>

{{-- Panel --}}
<div id="customize-panel"
     class="fixed top-0 right-0 h-full w-80 bg-white/95 backdrop-blur-xl shadow-2xl z-50 flex flex-col transform translate-x-full transition-transform duration-300 ease-[cubic-bezier(0.16,1,0.3,1)] border-l border-white/20">

    {{-- Panel Header --}}
    <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100 flex-shrink-0 bg-white">
        <div>
            <h3 class="font-extrabold text-gray-900 tracking-tight text-lg">Customize Layout</h3>
            <p class="text-xs text-gray-500 mt-0.5 font-medium"><span id="panel-visible-count">12</span>/12 widgets visible</p>
        </div>
        <button onclick="closeCustomizePanel()" class="w-8 h-8 rounded-full bg-gray-50 hover:bg-gray-200 flex items-center justify-center text-gray-500 hover:text-gray-900 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    {{-- Widget List --}}
    <div class="flex-1 overflow-y-auto px-5 py-4 space-y-1" id="customize-widget-list">
        {{-- Rows will be rendered and sorted by JS --}}
    </div>

    {{-- Panel Footer --}}
    <div class="px-6 py-5 border-t border-gray-100 space-y-3 flex-shrink-0 bg-gray-50/50">
        <button onclick="saveLayout()" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold text-sm py-3 rounded-xl shadow-md hover:shadow-lg transition-all focus:ring-4 focus:ring-blue-100">
            Save Changes
        </button>
        <button onclick="resetLayout()" class="w-full bg-white border border-gray-200 hover:border-red-200 hover:bg-red-50 hover:text-red-600 text-gray-600 font-bold text-sm py-3 rounded-xl transition-all">
            Reset Default Layout
        </button>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════════════════════
     STYLES
════════════════════════════════════════════════════════════════════════════ --}}
<style>
    /* Widget container uses flex-wrap so we can control order with CSS */
    #dashboard-widgets-container {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-start;
    }

    /* Stat widgets are 1/4 of the row on large screens */
    .stat-widget {
        flex: 1 0 200px;
        min-width: 0;
    }

    /* Full-width widgets */
    .full-widget {
        flex: 1 0 100%;
        width: 100%;
    }

    /* Widget header shared layout */
    .widget-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .widget-controls {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Hide button on widget */
    .widget-hide-btn {
        width: 24px;
        height: 24px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        color: #9ca3af;
        background: transparent;
        border: none;
        cursor: pointer;
        transition: all 0.1s;
        line-height: 1;
        padding: 0;
    }
    .widget-hide-btn:hover {
        background: #fee2e2;
        color: #ef4444;
    }

    /* Panel widget row */
    .panel-widget-row {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 8px;
        border-radius: 10px;
        transition: background 0.1s;
        cursor: default;
    }
    .panel-widget-row:hover {
        background: #f9fafb;
    }

    /* Toggle switch */
    .widget-toggle {
        position: relative;
        display: inline-block;
        width: 36px;
        height: 20px;
        flex-shrink: 0;
    }
    .widget-toggle input { display: none; }
    .widget-toggle .slider {
        position: absolute;
        inset: 0;
        background: #d1d5db;
        border-radius: 20px;
        cursor: pointer;
        transition: background 0.2s;
    }
    .widget-toggle .slider::before {
        content: '';
        position: absolute;
        left: 2px;
        top: 2px;
        width: 16px;
        height: 16px;
        background: white;
        border-radius: 50%;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        transition: transform 0.2s;
    }
    .widget-toggle input:checked + .slider { background: #2563eb; }
    .widget-toggle input:checked + .slider::before { transform: translateX(16px); }

    /* Backdrop visible state */
    #customize-backdrop.visible { display: block; opacity: 1; }
</style>

{{-- ════════════════════════════════════════════════════════════════════════════
     JAVASCRIPT — Widget Manager
════════════════════════════════════════════════════════════════════════════ --}}
<script>
(function () {
    const USER_ID   = {{ auth()->id() }};
    const PREF_KEY  = 'admin_dashboard_prefs_' + USER_ID;
    const TOTAL     = 12;

    // Canonical widget definitions (id, label, is full-width)
    const WIDGET_DEFS = [
        { id: 'total_members',        label: 'Total Members',           full: false },
        { id: 'new_members_month',    label: 'New Members This Month',  full: false },
        { id: 'total_ebooks',         label: 'Total Ebooks',            full: false },
        { id: 'total_authors',        label: 'Total Authors',           full: false },
        { id: 'total_categories',     label: 'Total Categories',        full: false },
        { id: 'active_subscriptions', label: 'Active Subscriptions',    full: false },
        { id: 'revenue_month',        label: 'Revenue This Month',      full: false },
        { id: 'pending_reviews',      label: 'Pending Reviews',         full: false },
        { id: 'activity_feed',        label: 'Live Activity Feed',      full: true  },
        { id: 'recent_transactions',  label: 'Recent Transactions',     full: true  },
        { id: 'top_ebooks',           label: 'Most Read Ebooks',        full: true  },
        { id: 'subscription_chart',   label: 'Subscriptions Chart',     full: true  },
    ];

    const DEFAULT_ORDER  = WIDGET_DEFS.map(w => w.id);
    const DEFAULT_HIDDEN = [];

    // ── State ──────────────────────────────────────────────────────────────────
    let prefs = loadPrefs();

    // ── Load / Save ────────────────────────────────────────────────────────────
    function loadPrefs() {
        try {
            const raw = localStorage.getItem(PREF_KEY);
            if (!raw) return { hidden: [...DEFAULT_HIDDEN], order: [...DEFAULT_ORDER] };
            const p = JSON.parse(raw);
            // Ensure any new widgets added later are appended to the order
            WIDGET_DEFS.forEach(w => {
                if (!p.order.includes(w.id)) p.order.push(w.id);
            });
            return p;
        } catch (e) {
            return { hidden: [...DEFAULT_HIDDEN], order: [...DEFAULT_ORDER] };
        }
    }

    function savePrefs() {
        localStorage.setItem(PREF_KEY, JSON.stringify(prefs));
    }

    // ── Apply layout to DOM ────────────────────────────────────────────────────
    function applyLayout() {
        prefs.order.forEach((id, idx) => {
            const el = document.getElementById('widget-' + id);
            if (el) {
                el.style.order = idx;
                el.style.display = prefs.hidden.includes(id) ? 'none' : '';
            }
        });
        updateCounts();
    }

    // ── Count visible widgets ──────────────────────────────────────────────────
    function updateCounts() {
        const visible = TOTAL - prefs.hidden.length;
        document.getElementById('visible-count').textContent    = visible;
        document.getElementById('panel-visible-count').textContent = visible;
    }

    // ── Panel open / close ─────────────────────────────────────────────────────
    window.openCustomizePanel = function () {
        renderPanelList();
        document.getElementById('customize-panel').classList.remove('translate-x-full');
        const bd = document.getElementById('customize-backdrop');
        bd.classList.remove('hidden');
        requestAnimationFrame(() => bd.classList.add('visible'));
    };

    window.closeCustomizePanel = function () {
        document.getElementById('customize-panel').classList.add('translate-x-full');
        const bd = document.getElementById('customize-backdrop');
        bd.classList.remove('visible');
        setTimeout(() => bd.classList.add('hidden'), 200);
    };

    // ── Render the panel list ──────────────────────────────────────────────────
    function renderPanelList() {
        const list = document.getElementById('customize-widget-list');
        list.innerHTML = '';

        prefs.order.forEach((id, idx) => {
            const def    = WIDGET_DEFS.find(w => w.id === id);
            if (!def) return;
            const hidden = prefs.hidden.includes(id);

            const row = document.createElement('div');
            row.className = 'panel-widget-row';
            row.dataset.widgetId = id;

            row.innerHTML = `
                <label class="widget-toggle">
                    <input type="checkbox" ${hidden ? '' : 'checked'} onchange="panelToggle('${id}', this.checked)">
                    <span class="slider"></span>
                </label>
                <span class="flex-1 text-sm font-medium text-gray-700">${def.label}</span>
                <div class="flex gap-1">
                    <button onclick="moveWidget('${id}', -1)"
                        class="w-7 h-7 rounded-lg border border-gray-200 flex items-center justify-center text-gray-400 hover:text-blue-600 hover:border-blue-300 transition-colors text-xs font-bold"
                        title="Move up" ${idx === 0 ? 'disabled style="opacity:0.35;cursor:not-allowed"' : ''}>↑</button>
                    <button onclick="moveWidget('${id}', 1)"
                        class="w-7 h-7 rounded-lg border border-gray-200 flex items-center justify-center text-gray-400 hover:text-blue-600 hover:border-blue-300 transition-colors text-xs font-bold"
                        title="Move down" ${idx === prefs.order.length - 1 ? 'disabled style="opacity:0.35;cursor:not-allowed"' : ''}>↓</button>
                </div>
            `;

            list.appendChild(row);
        });
    }

    // ── Toggle from panel ──────────────────────────────────────────────────────
    window.panelToggle = function (id, isVisible) {
        if (isVisible) {
            prefs.hidden = prefs.hidden.filter(h => h !== id);
        } else {
            if (!prefs.hidden.includes(id)) prefs.hidden.push(id);
        }
        applyLayout();
        savePrefs();
    };

    // ── Hide widget from dashboard quick-button ────────────────────────────────
    window.hideWidget = function (id) {
        if (!prefs.hidden.includes(id)) prefs.hidden.push(id);
        applyLayout();
        savePrefs();
    };

    // ── Reorder widget up or down ──────────────────────────────────────────────
    window.moveWidget = function (id, direction) {
        const idx = prefs.order.indexOf(id);
        const newIdx = idx + direction;
        if (newIdx < 0 || newIdx >= prefs.order.length) return;
        // Swap
        [prefs.order[idx], prefs.order[newIdx]] = [prefs.order[newIdx], prefs.order[idx]];
        applyLayout();
        savePrefs();
        renderPanelList(); // re-render so up/down buttons update
    };

    // ── Save button ────────────────────────────────────────────────────────────
    window.saveLayout = function () {
        savePrefs();
        closeCustomizePanel();
        // Flash feedback
        const btn = document.getElementById('open-customize-btn');
        const original = btn.innerHTML;
        btn.innerHTML = '<svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> <span class="text-green-600">Saved!</span>';
        setTimeout(() => { btn.innerHTML = original; updateCounts(); }, 1500);
    };

    // ── Reset layout ───────────────────────────────────────────────────────────
    window.resetLayout = function () {
        if (!confirm('Reset the dashboard to default? All your custom layout will be cleared.')) return;
        localStorage.removeItem(PREF_KEY);
        location.reload();
    };

    // ── Bootstrap on load ──────────────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', function () {
        applyLayout();
    });

})();
</script>
@endsection
