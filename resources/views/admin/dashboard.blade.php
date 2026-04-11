@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="space-y-4">

    {{-- ── Dashboard Header ─────────────────────────────────────────────────── --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-sm text-gray-400 mt-0.5">Welcome back, {{ auth()->user()->first_name ?: auth()->user()->email }}</p>
        </div>
        <button id="open-customize-btn"
            onclick="openCustomizePanel()"
            class="flex items-center gap-2 bg-white border border-gray-200 hover:border-blue-400 hover:text-blue-600 text-gray-600 text-sm font-medium px-4 py-2 rounded-xl shadow-sm transition-all duration-150">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
            Customize (<span id="visible-count">12</span>/12 visible)
        </button>
    </div>

    {{-- ── Alerts (non-widget, always visible) ─────────────────────────────── --}}
    @if($pendingReviews > 0)
    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <p class="text-sm font-medium text-yellow-800">
                <span class="font-bold">{{ $pendingReviews }}</span> review{{ $pendingReviews > 1 ? 's' : '' }} awaiting approval
            </p>
        </div>
        <a href="{{ route('admin.reviews.index', ['status' => 'pending']) }}" class="text-yellow-700 hover:text-yellow-900 text-sm font-medium hover:underline">Review now →</a>
    </div>
    @endif

    @if(isset($activeAnnouncements) && $activeAnnouncements->count() > 0)
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
        <div class="flex justify-between items-center mb-3">
            <h3 class="text-blue-800 font-bold flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                Active Announcements
            </h3>
            <a href="{{ route('admin.announcements.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 uppercase tracking-wider">Manage</a>
        </div>
        <div class="space-y-2">
            @foreach($activeAnnouncements as $ann)
                @php $bgColors = ['info'=>'bg-white text-blue-900 border-blue-100','warning'=>'bg-yellow-100 text-yellow-900 border-yellow-200','success'=>'bg-green-100 text-green-900 border-green-200','danger'=>'bg-red-100 text-red-900 border-red-200']; @endphp
                <div class="{{ $bgColors[$ann->type] ?? 'bg-white' }} px-4 py-2 rounded-lg border shadow-sm flex items-center justify-between gap-4">
                    <div class="font-medium text-sm truncate">{{ $ann->title }}</div>
                    <div class="text-xs uppercase font-bold opacity-75">{{ $ann->type }}</div>
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
             class="dashboard-widget stat-widget bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <div class="widget-header">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div class="widget-controls">
                    <span class="text-xs text-green-500 font-medium">+{{ $newMembersThisMonth }} this mo.</span>
                    <button class="widget-hide-btn" onclick="hideWidget('total_members')" title="Hide widget">✕</button>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-800 mt-3">{{ $totalMembers }}</p>
            <p class="text-sm text-gray-500 mt-1">Total Members</p>
        </div>

        {{-- ── [2] WIDGET: new_members_month ──────────────────────────────────── --}}
        <div id="widget-new_members_month" data-widget-id="new_members_month"
             class="dashboard-widget stat-widget bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <div class="widget-header">
                <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <div class="widget-controls">
                    <span class="text-xs text-gray-400 font-medium">{{ now()->format('M Y') }}</span>
                    <button class="widget-hide-btn" onclick="hideWidget('new_members_month')" title="Hide widget">✕</button>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-800 mt-3">{{ $newMembersThisMonth }}</p>
            <p class="text-sm text-gray-500 mt-1">New Members This Month</p>
        </div>

        {{-- ── [3] WIDGET: total_ebooks ────────────────────────────────────────── --}}
        <div id="widget-total_ebooks" data-widget-id="total_ebooks"
             class="dashboard-widget stat-widget bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <div class="widget-header">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <div class="widget-controls">
                    <button class="widget-hide-btn" onclick="hideWidget('total_ebooks')" title="Hide widget">✕</button>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-800 mt-3">{{ $totalEbooks }}</p>
            <p class="text-sm text-gray-500 mt-1">Total Ebooks</p>
        </div>

        {{-- ── [4] WIDGET: total_authors ───────────────────────────────────────── --}}
        <div id="widget-total_authors" data-widget-id="total_authors"
             class="dashboard-widget stat-widget bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <div class="widget-header">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <div class="widget-controls">
                    <button class="widget-hide-btn" onclick="hideWidget('total_authors')" title="Hide widget">✕</button>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-800 mt-3">{{ $totalAuthors }}</p>
            <p class="text-sm text-gray-500 mt-1">Total Authors</p>
        </div>

        {{-- ── [5] WIDGET: total_categories ────────────────────────────────────── --}}
        <div id="widget-total_categories" data-widget-id="total_categories"
             class="dashboard-widget stat-widget bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <div class="widget-header">
                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                </div>
                <div class="widget-controls">
                    <button class="widget-hide-btn" onclick="hideWidget('total_categories')" title="Hide widget">✕</button>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-800 mt-3">{{ $totalCategories }}</p>
            <p class="text-sm text-gray-500 mt-1">Total Categories</p>
        </div>

        {{-- ── [6] WIDGET: active_subscriptions ───────────────────────────────── --}}
        <div id="widget-active_subscriptions" data-widget-id="active_subscriptions"
             class="dashboard-widget stat-widget bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <div class="widget-header">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                </div>
                <div class="widget-controls">
                    <button class="widget-hide-btn" onclick="hideWidget('active_subscriptions')" title="Hide widget">✕</button>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-800 mt-3">{{ $activeSubscriptions }}</p>
            <p class="text-sm text-gray-500 mt-1">Active Subscriptions</p>
        </div>

        {{-- ── [7] WIDGET: revenue_month ───────────────────────────────────────── --}}
        <div id="widget-revenue_month" data-widget-id="revenue_month"
             class="dashboard-widget stat-widget bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <div class="widget-header">
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <div class="widget-controls">
                    <span class="text-xs text-gray-400">This month</span>
                    <button class="widget-hide-btn" onclick="hideWidget('revenue_month')" title="Hide widget">✕</button>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-800 mt-3">₱{{ number_format($revenueThisMonth, 2) }}</p>
            <p class="text-sm text-gray-500 mt-1">Revenue This Month</p>
        </div>

        {{-- ── [8] WIDGET: pending_reviews ─────────────────────────────────────── --}}
        <div id="widget-pending_reviews" data-widget-id="pending_reviews"
             class="dashboard-widget stat-widget bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <div class="widget-header">
                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                </div>
                <div class="widget-controls">
                    @if($pendingReviews > 0)
                    <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $pendingReviews }}</span>
                    @endif
                    <button class="widget-hide-btn" onclick="hideWidget('pending_reviews')" title="Hide widget">✕</button>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-800 mt-3">{{ $pendingReviews }}</p>
            <p class="text-sm text-gray-500 mt-1">Pending Reviews</p>
        </div>

        {{-- ── [9] WIDGET: activity_feed ───────────────────────────────────────── --}}
        <div id="widget-activity_feed" data-widget-id="activity_feed"
             class="dashboard-widget full-widget bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="inline-block w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                    <h2 class="font-semibold text-gray-800">Live Activity Feed</h2>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.activity-logs.index') }}" class="text-xs text-blue-600 hover:underline">View all →</a>
                    <button class="widget-hide-btn" onclick="hideWidget('activity_feed')" title="Hide widget">✕</button>
                </div>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($activityFeed as $log)
                <div class="px-6 py-3 flex items-start gap-3 hover:bg-gray-50 transition-colors">
                    <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-800 font-medium truncate">{{ $log->action }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ Str::limit($log->description, 80) }}</p>
                        <p class="text-xs text-gray-300 mt-0.5">{{ $log->user?->email ?? 'System' }} · {{ $log->created_at?->diffForHumans() }}</p>
                    </div>
                    <span class="text-xs text-gray-300 whitespace-nowrap">{{ $log->module }}</span>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-gray-400 text-sm">No recent activity.</div>
                @endforelse
            </div>
        </div>

        {{-- ── [10] WIDGET: recent_transactions ────────────────────────────────── --}}
        <div id="widget-recent_transactions" data-widget-id="recent_transactions"
             class="dashboard-widget full-widget bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-semibold text-gray-800">Recent Transactions</h2>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.transactions.index') }}" class="text-xs text-blue-600 hover:underline">View all →</a>
                    <button class="widget-hide-btn" onclick="hideWidget('recent_transactions')" title="Hide widget">✕</button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                        <tr>
                            <th class="px-6 py-3 text-left">Member</th>
                            <th class="px-6 py-3 text-left">Plan</th>
                            <th class="px-6 py-3 text-left">Amount</th>
                            <th class="px-6 py-3 text-left">Status</th>
                            <th class="px-6 py-3 text-left">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTransactions as $tx)
                        <tr class="border-t border-gray-50 hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-3 font-medium text-gray-800">{{ $tx->member?->full_name ?: '—' }}</td>
                            <td class="px-6 py-3 text-gray-500">{{ $tx->plan?->name ?: '—' }}</td>
                            <td class="px-6 py-3 font-semibold text-emerald-600">₱{{ number_format($tx->amount, 2) }}</td>
                            <td class="px-6 py-3">
                                @php $txColors = ['completed'=>'bg-green-100 text-green-700','pending'=>'bg-yellow-100 text-yellow-700','failed'=>'bg-red-100 text-red-700']; @endphp
                                <span class="px-2 py-0.5 rounded text-xs font-medium {{ $txColors[$tx->status] ?? 'bg-gray-100 text-gray-600' }}">{{ ucfirst($tx->status) }}</span>
                            </td>
                            <td class="px-6 py-3 text-gray-400 text-xs">{{ $tx->created_at->format('M d, Y') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-6 py-6 text-center text-gray-400">No transactions yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ── [11] WIDGET: top_ebooks ─────────────────────────────────────────── --}}
        <div id="widget-top_ebooks" data-widget-id="top_ebooks"
             class="dashboard-widget full-widget bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-semibold text-gray-800">Most Read Ebooks</h2>
                <button class="widget-hide-btn" onclick="hideWidget('top_ebooks')" title="Hide widget">✕</button>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($topEbooks as $i => $ebook)
                <div class="px-6 py-3 flex items-center gap-4 hover:bg-gray-50 transition-colors">
                    <span class="text-2xl font-black text-gray-200 w-8 text-center leading-none">{{ $i + 1 }}</span>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate">{{ $ebook->title }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ $ebook->authors->pluck('full_name')->join(', ') ?: 'Unknown' }}</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-sm font-bold text-blue-600">{{ $ebook->ebook_access_count }}</p>
                        <p class="text-xs text-gray-400">reads</p>
                    </div>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-gray-400 text-sm">No reading data yet.</div>
                @endforelse
            </div>
        </div>

        {{-- ── [12] WIDGET: subscription_chart ─────────────────────────────────── --}}
        <div id="widget-subscription_chart" data-widget-id="subscription_chart"
             class="dashboard-widget full-widget bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="font-semibold text-gray-800">Subscriptions Chart</h2>
                    <p class="text-xs text-gray-400 mt-0.5">New subscriptions over the last 6 months</p>
                </div>
                <button class="widget-hide-btn" onclick="hideWidget('subscription_chart')" title="Hide widget">✕</button>
            </div>
            <div class="flex items-end gap-3 h-32">
                @php $maxCount = max($subscriptionChart->pluck('count')->max(), 1); @endphp
                @foreach($subscriptionChart as $point)
                @php $barHeight = max(4, round(($point['count'] / $maxCount) * 100)); @endphp
                <div class="flex-1 flex flex-col items-center gap-1">
                    <span class="text-xs font-bold text-blue-600">{{ $point['count'] }}</span>
                    <div class="w-full bg-blue-500 hover:bg-blue-600 transition-colors rounded-t-md" style="height: {{ $barHeight }}%;" title="{{ $point['count'] }} subscriptions"></div>
                    <span class="text-xs text-gray-400">{{ $point['label'] }}</span>
                </div>
                @endforeach
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100 flex items-center gap-6 text-sm">
                <div><span class="text-gray-400">Total Active:</span> <span class="font-bold text-gray-800">{{ $activeSubscriptions }}</span></div>
                <div><span class="text-gray-400">Revenue:</span> <span class="font-bold text-emerald-600">₱{{ number_format($revenueThisMonth, 2) }}</span></div>
                <div><span class="text-gray-400">Premium:</span> <span class="font-bold text-purple-600">{{ $premiumMembers }}</span></div>
            </div>
        </div>

        {{-- ── Quick Links (always visible, not a widget) ──────────────────────── --}}
        <div class="w-full bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h2 class="text-base font-semibold text-gray-700 mb-4">Quick Actions</h2>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.ebooks.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">+ Add Ebook</a>
                <a href="{{ route('admin.reviews.index', ['status' => 'pending']) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                    Review Approvals
                    @if($pendingReviews > 0) <span class="bg-white text-yellow-700 text-xs font-bold px-1.5 py-0.5 rounded-full">{{ $pendingReviews }}</span> @endif
                </a>
                <a href="{{ route('admin.subscription-plans.index') }}" class="bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">Manage Plans</a>
                <a href="{{ route('admin.subscriptions.index') }}" class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">View Subscriptions</a>
                <a href="{{ route('admin.transactions.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">View Transactions</a>
            </div>
        </div>

    </div>{{-- end #dashboard-widgets-container --}}
</div>{{-- end space-y-4 --}}

{{-- ════════════════════════════════════════════════════════════════════════════
     CUSTOMIZE PANEL — slide-in from right
════════════════════════════════════════════════════════════════════════════ --}}

{{-- Backdrop --}}
<div id="customize-backdrop" onclick="closeCustomizePanel()"
     class="fixed inset-0 bg-black/30 backdrop-blur-sm z-40 hidden opacity-0 transition-opacity duration-200"></div>

{{-- Panel --}}
<div id="customize-panel"
     class="fixed top-0 right-0 h-full w-80 bg-white shadow-2xl z-50 flex flex-col transform translate-x-full transition-transform duration-300 ease-in-out">

    {{-- Panel Header --}}
    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 flex-shrink-0">
        <div>
            <h3 class="font-bold text-gray-900">Customize Widgets</h3>
            <p class="text-xs text-gray-400 mt-0.5"><span id="panel-visible-count">12</span>/12 widgets visible</p>
        </div>
        <button onclick="closeCustomizePanel()" class="w-8 h-8 rounded-lg hover:bg-gray-100 flex items-center justify-center text-gray-500 hover:text-gray-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    {{-- Widget List --}}
    <div class="flex-1 overflow-y-auto px-4 py-3" id="customize-widget-list">
        {{-- Rows will be rendered and sorted by JS --}}
    </div>

    {{-- Panel Footer --}}
    <div class="px-5 py-4 border-t border-gray-100 space-y-2 flex-shrink-0">
        <button onclick="saveLayout()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm py-2.5 rounded-xl transition-colors">
            Save Layout
        </button>
        <button onclick="resetLayout()" class="w-full bg-white border border-gray-200 hover:border-red-300 hover:text-red-600 text-gray-600 font-medium text-sm py-2.5 rounded-xl transition-colors">
            Reset to Default
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
