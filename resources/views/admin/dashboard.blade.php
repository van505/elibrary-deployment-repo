@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div x-data="{
    customizeOpen: false,
    saving: false,
    widgets: {
        metrics:             {{ $widgets['metrics'] ? 'true' : 'false' }},
        subscriptions_chart: {{ $widgets['subscriptions_chart'] ? 'true' : 'false' }},
        recent_transactions: {{ $widgets['recent_transactions'] ? 'true' : 'false' }},
        action_required:     {{ $widgets['action_required'] ? 'true' : 'false' }},
        most_read_ebooks:    {{ $widgets['most_read_ebooks'] ? 'true' : 'false' }},
        recent_members:      {{ $widgets['recent_members'] ? 'true' : 'false' }},
        activity_feed:       {{ $widgets['activity_feed'] ? 'true' : 'false' }},
    },
    async saveWidgets() {
        this.saving = true;
        await fetch('{{ route('admin.dashboard.widgets') }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ widgets: this.widgets })
        });
        this.saving = false;
        this.customizeOpen = false;
        window.location.reload();
    }
}">

<div class="space-y-5">

{{-- ── ROW 1: Welcome Banner ──────────────────────────────────────────── --}}
<div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-900 via-indigo-800 to-blue-900 px-6 py-5 shadow-lg border border-indigo-700/40 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div class="absolute inset-0 opacity-5 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
    <div class="relative z-10">
        <h1 class="text-xl font-extrabold text-white tracking-tight">Welcome back, {{ auth()->user()->first_name ?: auth()->user()->email }} 👋</h1>
        <p class="text-indigo-300 text-sm mt-0.5 flex items-center gap-1.5">
            <span class="w-1.5 h-1.5 rounded-full bg-green-400 animate-pulse inline-block"></span>
            System running smoothly &bull; {{ now()->format('l, F j Y') }}
        </p>
    </div>
    <div class="relative z-10 flex items-center gap-2">
        <button @click="customizeOpen = true"
                class="flex items-center gap-1.5 bg-indigo-700 hover:bg-indigo-600 text-white text-xs px-3 py-2 rounded-lg transition-all duration-200">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
            Customize
        </button>
        <button @click="$dispatch('open-ebook-drawer')" title="Add Ebook"
           class="flex items-center gap-1.5 bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-semibold px-3 py-2 rounded-xl transition-all">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Add Ebook
        </button>
        <a href="{{ route('admin.subscription-plans.index') }}" title="Manage Plans"
           class="flex items-center gap-1.5 bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-semibold px-3 py-2 rounded-xl transition-all">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
            Plans
        </a>
        <a href="{{ route('admin.transactions.index') }}" title="Transactions"
           class="flex items-center gap-1.5 bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-semibold px-3 py-2 rounded-xl transition-all">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            Transactions
        </a>
    </div>
</div>

@if($widgets['metrics'])
{{-- ── ROW 2: 6 Metric Cards ──────────────────────────────────────────── --}}
<div class="grid grid-cols-2 lg:grid-cols-6 gap-4">
    @php
    $metrics = [
        ['label'=>'New This Mo.',    'value'=> $newMembersThisMonth,                'color'=>'text-teal-600',   'bg'=>'bg-teal-50',   'icon'=>'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
        ['label'=>'Total Ebooks',    'value'=> $totalEbooks,                        'color'=>'text-indigo-600', 'bg'=>'bg-indigo-50', 'icon'=>'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
        ['label'=>'Authors',         'value'=> $totalAuthors,                       'color'=>'text-purple-600', 'bg'=>'bg-purple-50', 'icon'=>'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
        ['label'=>'Categories',      'value'=> $totalCategories,                    'color'=>'text-violet-600', 'bg'=>'bg-violet-50', 'icon'=>'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z'],
        ['label'=>'Active Subs',     'value'=> $activeSubscriptions,                'color'=>'text-green-600',  'bg'=>'bg-green-50',  'icon'=>'M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z'],
        ['label'=>'Revenue',         'value'=>'₱'.number_format($revenueThisMonth), 'color'=>'text-emerald-600','bg'=>'bg-emerald-50','icon'=>'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z'],
    ];
    @endphp
    @foreach($metrics as $m)
    <div class="bg-white rounded-xl shadow-md border border-gray-100 px-4 py-3 flex items-center gap-3 hover:shadow-lg transition-shadow">
        <div class="w-9 h-9 rounded-lg {{ $m['bg'] }} flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 {{ $m['color'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $m['icon'] }}"/>
            </svg>
        </div>
        <div class="min-w-0">
            <p class="text-lg font-extrabold text-gray-900 leading-none">{{ $m['value'] }}</p>
            <p class="text-xs text-gray-500 mt-0.5 truncate">{{ $m['label'] }}</p>
        </div>
    </div>
    @endforeach
</div>
@endif

{{-- ── ROWS 3-4: Split View ───────────────────────────────────────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- Left: col-span-2 --}}
    <div class="lg:col-span-2 space-y-5">

        @if($widgets['subscriptions_chart'])
        {{-- Subscription Growth Chart --}}
        <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h2 class="font-bold text-gray-900 text-sm">Subscriptions Growth</h2>
                    <p class="text-xs text-gray-500 mt-0.5">New subscriptions over the last 6 months</p>
                </div>
                <div class="flex items-center gap-4 text-xs">
                    <span class="text-gray-400 font-medium">Active: <span class="font-bold text-gray-900">{{ $activeSubscriptions }}</span></span>
                    <span class="text-gray-400 font-medium">Premium: <span class="font-bold text-indigo-600">{{ $premiumMembers }}</span></span>
                </div>
            </div>
            <div class="px-5 py-4">
                <div id="subscriptions-chart" class="w-full h-48"></div>
            </div>
        </div>
        
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var options = {
                    series: [{
                        name: 'New Subscriptions',
                        data: {!! json_encode($subscriptionChart->pluck('count')) !!}
                    }],
                    chart: {
                        height: 192, // h-48 = 12rem = 192px
                        type: 'area',
                        fontFamily: 'inherit',
                        toolbar: { show: false },
                        zoom: { enabled: false }
                    },
                    colors: ['#4F46E5'], // Tailwind indigo-600
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.45,
                            opacityTo: 0.05,
                            stops: [50, 100, 100]
                        }
                    },
                    dataLabels: { enabled: false },
                    stroke: {
                        curve: 'smooth',
                        width: 2
                    },
                    xaxis: {
                        categories: {!! json_encode($subscriptionChart->pluck('label')) !!},
                        axisBorder: { show: false },
                        axisTicks: { show: false },
                        labels: {
                            style: { colors: '#9CA3AF', fontSize: '11px', fontWeight: 500 }
                        }
                    },
                    yaxis: {
                        show: false, // Hide y-axis for a cleaner SaaS look
                    },
                    grid: {
                        show: true,
                        borderColor: '#F3F4F6',
                        strokeDashArray: 4,
                        padding: { top: 0, right: 0, bottom: 0, left: 10 }
                    },
                    tooltip: {
                        theme: 'light',
                        y: { formatter: function (val) { return val + " subs" } }
                    }
                };

                var chart = new ApexCharts(document.querySelector("#subscriptions-chart"), options);
                chart.render();
            });
        </script>
        @endif

        @if($widgets['recent_transactions'])
        {{-- Recent Transactions --}}
        <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-bold text-gray-900 text-sm">Recent Transactions</h2>
                <a href="{{ route('admin.transactions.index') }}" class="text-xs text-blue-600 hover:text-blue-800 font-semibold transition-colors">View all →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-4 py-2.5 text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                            <th class="px-4 py-2.5 text-xs font-medium text-gray-500 uppercase tracking-wider">Plan</th>
                            <th class="px-4 py-2.5 text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-4 py-2.5 text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($recentTransactions as $tx)
                        @php $sc=['completed'=>'bg-green-50 text-green-700','pending'=>'bg-yellow-50 text-yellow-700','failed'=>'bg-red-50 text-red-600']; @endphp
                        <tr class="hover:bg-gray-50/60 transition-colors">
                            <td class="px-4 py-3 font-medium text-gray-900 text-xs">{{ $tx->member?->full_name ?: '—' }}</td>
                            <td class="px-4 py-3 text-gray-500 text-xs">{{ $tx->plan?->name ?: '—' }}</td>
                            <td class="px-4 py-3 font-bold text-gray-900 text-xs">₱{{ number_format($tx->amount,2) }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1 text-xs font-medium px-2 py-0.5 rounded-full {{ $sc[$tx->status] ?? 'bg-gray-100 text-gray-500' }}">{{ ucfirst($tx->status) }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-4 py-6 text-center text-gray-400 text-xs">No transactions yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    </div>

    {{-- Right: col-span-1 --}}
    <div class="lg:col-span-1 space-y-5">

        @if($widgets['action_required'])
        {{-- Pending Approvals Alert --}}
        @if($pendingReviews > 0)
        <div class="bg-amber-50 border border-amber-200 rounded-xl shadow-md p-4">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-lg bg-amber-400 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-amber-900">Action Required</p>
                    <p class="text-xs text-amber-700 mt-0.5"><span class="font-bold">{{ $pendingReviews }}</span> review{{ $pendingReviews > 1 ? 's' : '' }} awaiting approval</p>
                    <a href="{{ route('admin.reviews.index', ['status'=>'pending']) }}" class="mt-2 inline-flex items-center gap-1 text-xs font-bold text-amber-900 bg-white border border-amber-200 px-2.5 py-1 rounded-lg hover:bg-amber-100 transition-colors">
                        Review Now <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        </div>
        @else
        <div class="bg-green-50 border border-green-200 rounded-xl shadow-md p-4 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-green-500 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div>
                <p class="text-sm font-bold text-green-900">All caught up!</p>
                <p class="text-xs text-green-600">No pending review approvals.</p>
            </div>
        </div>
        @endif
        @endif

        @if($widgets['most_read_ebooks'])
        {{-- Most Read Ebooks Top 5 --}}
        <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
            <div class="px-4 py-3.5 border-b border-gray-100 flex items-center gap-2">
                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                <h2 class="font-bold text-gray-900 text-sm">Most Read Ebooks</h2>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($topEbooks as $i => $ebook)
                <div class="px-4 py-3 flex items-center gap-3 hover:bg-gray-50/60 transition-colors">
                    <span class="text-sm font-black {{ $i < 3 ? 'text-gray-800' : 'text-gray-300' }} w-5 text-center">{{ $i+1 }}</span>
                    <div class="w-8 h-10 bg-gray-100 rounded border border-gray-200 overflow-hidden flex-shrink-0 flex items-center justify-center">
                        @if($ebook->cover_image)
                            <img src="{{ Storage::url($ebook->cover_image) }}" class="w-full h-full object-cover" alt="">
                        @else
                            <svg class="w-4 h-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-gray-900 truncate">{{ $ebook->title }}</p>
                        <p class="text-[10px] text-gray-400 truncate">{{ $ebook->authors->pluck('full_name')->join(', ') ?: 'Unknown' }}</p>
                    </div>
                    <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md flex-shrink-0">{{ $ebook->ebook_access_count }}</span>
                </div>
                @empty
                <div class="px-4 py-6 text-center text-xs text-gray-400">No reading data yet.</div>
                @endforelse
            </div>
        </div>
        @endif

        @if($widgets['recent_members'])
        <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
            <div class="px-4 py-3.5 border-b border-gray-100">
                <h2 class="font-bold text-gray-900 text-sm">Recent Members</h2>
            </div>
            <div class="divide-y divide-gray-50">
                @foreach(\App\Models\Member::with('user')->latest()->take(5)->get() as $member)
                <div class="px-4 py-3 flex items-center gap-3 hover:bg-gray-50/60 transition-colors">
                    <div class="w-7 h-7 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center text-xs font-bold flex-shrink-0">{{ strtoupper(substr($member->first_name ?? '?', 0, 1)) }}</div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-gray-900 truncate">{{ $member->first_name }} {{ $member->last_name }}</p>
                        <p class="text-[10px] text-gray-400 truncate">{{ $member->user?->email }}</p>
                    </div>
                    <span class="text-[10px] text-gray-400">{{ $member->created_at->diffForHumans() }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if($widgets['activity_feed'])
        {{-- Live Activity Feed --}}
        <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
            <div class="px-4 py-3.5 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-blue-500"></span>
                    </span>
                    <h2 class="font-bold text-gray-900 text-sm">Live Activity</h2>
                </div>
                <a href="{{ route('admin.activity-logs.index') }}" class="text-xs text-blue-600 hover:text-blue-800 font-semibold transition-colors">View all →</a>
            </div>
            <div class="divide-y divide-gray-50 max-h-64 overflow-y-auto">
                @forelse($activityFeed as $log)
                <div class="px-4 py-3 flex items-start gap-2.5 hover:bg-gray-50/60 transition-colors">
                    <div class="w-7 h-7 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-gray-900 truncate">{{ $log->action }}</p>
                        <p class="text-[10px] text-gray-400 truncate mt-0.5">{{ Str::limit($log->description, 60) }}</p>
                        <p class="text-[10px] text-gray-400 mt-0.5">{{ $log->created_at?->diffForHumans() }}</p>
                    </div>
                    <span class="text-[9px] font-bold uppercase tracking-wider bg-gray-100 text-gray-500 px-1.5 py-0.5 rounded flex-shrink-0">{{ $log->module }}</span>
                </div>
                @empty
                <div class="px-4 py-6 text-center text-xs text-gray-400">No recent activity.</div>
                @endforelse
            </div>
        </div>
        @endif

    </div>
</div>
{{-- ── Active Announcements ────────────────────────────────────────────── --}}
@if(isset($activeAnnouncements) && $activeAnnouncements->count() > 0)
<div class="bg-white rounded-xl shadow-md border border-gray-100 p-4">
    <div class="flex items-center justify-between mb-3">
        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-blue-500"></span> Active Announcements
        </h3>
        <a href="{{ route('admin.announcements.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition-colors">Manage →</a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
        @foreach($activeAnnouncements as $ann)
        @php $bg=['info'=>'bg-blue-50 border-blue-200 text-blue-900','warning'=>'bg-yellow-50 border-yellow-200 text-yellow-900','success'=>'bg-green-50 border-green-200 text-green-900','danger'=>'bg-red-50 border-red-200 text-red-900']; @endphp
        <div class="px-3 py-2.5 rounded-lg border text-xs {{ $bg[$ann->type] ?? 'bg-gray-50 border-gray-200 text-gray-700' }}">
            <span class="font-black uppercase text-[9px] opacity-60 tracking-widest block">{{ $ann->type }}</span>
            <span class="font-semibold text-xs leading-tight">{{ $ann->title }}</span>
        </div>
        @endforeach
    </div>
</div>
@endif

</div> {{-- End space-y-5 main content --}}

{{-- ── Customize Slide-over ───────────────────────────────────────────── --}}
<div x-show="customizeOpen" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-[90]" 
     x-transition:enter="ease-in-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="ease-in-out duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click="customizeOpen = false" style="display:none;"></div>
     
<div x-show="customizeOpen" 
     x-transition:enter="transform transition ease-in-out duration-500"
     x-transition:enter-start="translate-x-full"
     x-transition:enter-end="translate-x-0"
     x-transition:leave="transform transition ease-in-out duration-500"
     x-transition:leave-start="translate-x-0"
     x-transition:leave-end="translate-x-full"
     class="fixed inset-y-0 right-0 z-[100] w-80 bg-white shadow-2xl flex flex-col pointer-events-auto" style="display:none;">
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
        <h2 class="text-base font-semibold text-gray-900">Customize Dashboard</h2>
        <button @click="customizeOpen = false" class="p-1.5 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-all">✕</button>
    </div>
    <div class="flex-1 px-6 py-5 overflow-y-auto space-y-1">
        <p class="text-xs text-gray-400 mb-4">Toggle widgets on or off. Click Save to apply.</p>
        <template x-for="(label, key) in {
            metrics: 'Metrics Row',
            subscriptions_chart: 'Subscriptions Chart',
            recent_transactions: 'Recent Transactions',
            action_required: 'Action Required Alert',
            most_read_ebooks: 'Most Read Ebooks',
            recent_members: 'Recent Members',
            activity_feed: 'Activity Feed'
        }" :key="key">
            <div class="flex items-center justify-between py-3 border-b border-gray-100">
                <span class="text-sm text-gray-700" x-text="label"></span>
                <button @click="widgets[key] = !widgets[key]"
                        :class="widgets[key] ? 'bg-indigo-600' : 'bg-gray-200'"
                        class="relative inline-flex h-5 w-9 rounded-full transition-colors duration-200 focus:outline-none">
                    <span :class="widgets[key] ? 'translate-x-4' : 'translate-x-0.5'"
                          class="inline-block w-4 h-4 mt-0.5 bg-white rounded-full shadow transform transition-transform duration-200"></span>
                </button>
            </div>
        </template>
    </div>
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        <button @click="saveWidgets()" :disabled="saving"
                class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-60 text-white text-sm font-medium rounded-lg transition-all">
            <span x-text="saving ? 'Saving...' : 'Save Layout'"></span>
        </button>
    </div>
</div>

</div>

<x-admin.ebook-drawer 
    :categories="\App\Models\Category::orderBy('name')->get()" 
    :authors="\App\Models\Author::orderBy('last_name')->get()" 
/>

</div>
@endsection
