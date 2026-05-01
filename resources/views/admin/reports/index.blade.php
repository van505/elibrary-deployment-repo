@extends('layouts.admin')
@section('title', 'Analytics & Reports')

@push('breadcrumbs')
<nav class="flex items-center text-sm" aria-label="Breadcrumb">
    <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-gray-600 transition-colors duration-150">Dashboard</a>
    <span class="text-gray-300 mx-1.5 select-none">›</span>
    <span class="text-gray-400 mx-1.5">Admin</span>
    <span class="text-gray-300 mx-1.5 select-none">›</span>
    <span class="text-gray-700 font-medium">Reports</span>
</nav>
@endpush

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@endpush

@section('content')
<div class="space-y-6">

    {{-- Row 1: Filters --}}
    <form action="{{ route('admin.reports.index') }}" method="GET" class="flex flex-wrap items-center gap-3 bg-white p-4 rounded-xl shadow-md border border-gray-100">
        <div class="flex items-center gap-2">
            <label class="text-sm font-medium text-gray-600 hidden md:block">Date Range:</label>
            <input type="date" name="report_date_start" value="{{ request('report_date_start') }}" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none text-gray-700" title="Start Date">
            <span class="text-gray-400 text-sm">to</span>
            <input type="date" name="report_date_end" value="{{ request('report_date_end') }}" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none text-gray-700" title="End Date">
        </div>
        
        <div class="h-6 w-px bg-gray-200 mx-1 hidden sm:block"></div>

        <select name="category_id" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none text-gray-700 max-w-xs">
            <option value="">All Categories</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>

        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors shadow-sm ml-auto sm:ml-0">
            Apply Filters
        </button>
        
        @if(request()->hasAny(['report_date_start', 'report_date_end', 'category_id']))
            <a href="{{ route('admin.reports.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors underline underline-offset-2">
                Clear
            </a>
        @endif
    </form>

    {{-- Row 2: Metrics --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Accesses --}}
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 flex flex-col justify-center relative group hover:shadow-lg transition-shadow">
            <div class="absolute top-6 right-6 w-10 h-10 rounded-full flex items-center justify-center bg-indigo-50 text-indigo-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            </div>
            <p class="text-sm font-medium text-gray-500 mb-1">Accesses This Month</p>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($accessesThisMonth) }}</p>
            <div class="mt-2 flex items-center text-sm">
                <span class="text-emerald-600 font-medium flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    12%
                </span>
                <span class="text-gray-500 ml-2">vs last month</span>
            </div>
        </div>
        
        {{-- Total Reviews --}}
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 flex flex-col justify-center relative group hover:shadow-lg transition-shadow">
            <div class="absolute top-6 right-6 w-10 h-10 rounded-full flex items-center justify-center bg-amber-50 text-amber-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
            </div>
            <p class="text-sm font-medium text-gray-500 mb-1">Total Reviews</p>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($totalReviews) }}</p>
            <div class="mt-2 flex items-center text-sm">
                <span class="text-emerald-600 font-medium flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    8%
                </span>
                <span class="text-gray-500 ml-2">vs last month</span>
            </div>
        </div>

        {{-- Approved Reviews --}}
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 flex flex-col justify-center relative group hover:shadow-lg transition-shadow">
            <div class="absolute top-6 right-6 w-10 h-10 rounded-full flex items-center justify-center bg-emerald-50 text-emerald-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-sm font-medium text-gray-500 mb-1">Approved Reviews</p>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($approvedReviews) }}</p>
            <div class="mt-2 flex items-center text-sm">
                <span class="text-emerald-600 font-medium flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    5%
                </span>
                <span class="text-gray-500 ml-2">vs last month</span>
            </div>
        </div>

        {{-- Review Approval Rate --}}
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 flex flex-col justify-center relative group hover:shadow-lg transition-shadow">
            <div class="absolute top-6 right-6 w-10 h-10 rounded-full flex items-center justify-center bg-sky-50 text-sky-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
            <p class="text-sm font-medium text-gray-500 mb-1">Review Approval Rate</p>
            <p class="text-3xl font-bold {{ $approvalRate >= 70 ? 'text-emerald-600' : 'text-amber-500' }}">{{ $approvalRate }}%</p>
            <div class="mt-2 flex items-center text-sm">
                <span class="{{ $approvalRate >= 70 ? 'text-emerald-600' : 'text-amber-500' }} font-medium flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Stable
                </span>
                <span class="text-gray-500 ml-2">vs last month</span>
            </div>
        </div>
    </div>

    {{-- Row 3: Charts --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Revenue Bar Chart --}}
        <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-50">
                <h2 class="text-base font-bold text-gray-900">Revenue - Last 6 Months</h2>
            </div>
            <div class="p-6">
                <div id="revenue-chart"></div>
            </div>
        </div>

        {{-- Member Growth Line Chart --}}
        <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-50">
                <h2 class="text-base font-bold text-gray-900">Member Growth - Last 6 Months</h2>
            </div>
            <div class="p-6">
                <div id="growth-chart"></div>
            </div>
        </div>
    </div>

    {{-- Row 4: Data Tables --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Most Read Ebooks --}}
        <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-50">
                <h2 class="text-base font-bold text-gray-900">Most Read Ebooks</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider font-semibold">
                        <tr>
                            <th class="px-6 py-4">#</th>
                            <th class="px-6 py-4">Title</th>
                            <th class="px-6 py-4 text-right">Accesses</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($topEbooks as $access)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-400">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.ebooks.show', $access->ebook_id) }}" class="font-bold text-gray-900 hover:text-indigo-600 transition-colors">
                                    {{ $access->ebook->title ?? '—' }}
                                </a>
                                <div class="text-xs text-gray-500 mt-0.5">{{ $access->ebook->authors->pluck('full_name')->join(', ') ?: '—' }}</div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="bg-slate-100 text-slate-700 px-2.5 py-0.5 rounded-full text-xs font-medium inline-block">
                                    {{ number_format($access->access_count) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="px-6 py-8 text-center text-gray-400 italic">No ebook accesses in this period.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Most Active Members --}}
        <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-50">
                <h2 class="text-base font-bold text-gray-900">Most Active Members</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider font-semibold">
                        <tr>
                            <th class="px-6 py-4">#</th>
                            <th class="px-6 py-4">Member</th>
                            <th class="px-6 py-4 text-right">Accesses</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($topMembers as $access)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-400">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @php
                                        $initial = strtoupper(substr($access->member?->full_name ?? '?', 0, 1));
                                        $colors = ['bg-blue-100 text-blue-700', 'bg-emerald-100 text-emerald-700', 'bg-amber-100 text-amber-700', 'bg-purple-100 text-purple-700', 'bg-pink-100 text-pink-700'];
                                        $colorClass = $colors[$loop->index % count($colors)];
                                    @endphp
                                    <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm {{ $colorClass }}">
                                        {{ $initial }}
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.members.show', $access->member_id) }}" class="font-bold text-gray-900 hover:text-indigo-600 transition-colors">
                                            {{ $access->member?->full_name ?: 'Unknown Member' }}
                                        </a>
                                        <div class="text-xs text-gray-500 mt-0.5">{{ $access->member?->member_code ?? '—' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="bg-slate-100 text-slate-700 px-2.5 py-0.5 rounded-full text-xs font-medium inline-block">
                                    {{ number_format($access->access_count) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="px-6 py-8 text-center text-gray-400 italic">No active members in this period.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    
    // Revenue Bar Chart
    var revenueOptions = {
        series: [{
            name: 'Revenue (₱)',
            data: {!! json_encode($revenueData) !!}
        }],
        chart: {
            type: 'bar',
            height: 300,
            fontFamily: 'inherit',
            toolbar: { show: false }
        },
        colors: ['#4F46E5'], // Tailwind indigo-600
        plotOptions: {
            bar: {
                borderRadius: 4,
                borderRadiusApplication: 'end',
                columnWidth: '40%',
            }
        },
        dataLabels: { enabled: false },
        xaxis: {
            categories: {!! json_encode($revenueLabels) !!},
            axisBorder: { show: false },
            axisTicks: { show: false },
            labels: {
                style: { colors: '#6B7280', fontSize: '12px' }
            }
        },
        yaxis: {
            labels: {
                formatter: function (value) {
                    return "₱" + value.toLocaleString();
                },
                style: { colors: '#6B7280', fontSize: '12px' }
            }
        },
        grid: {
            borderColor: '#F3F4F6',
            strokeDashArray: 4,
            yaxis: { lines: { show: true } }
        },
        tooltip: {
            theme: 'light',
            y: {
                formatter: function (val) {
                    return "₱" + val.toLocaleString()
                }
            }
        }
    };

    var revenueChart = new ApexCharts(document.querySelector("#revenue-chart"), revenueOptions);
    revenueChart.render();

    // Member Growth Area Chart
    var growthOptions = {
        series: [{
            name: 'New Members',
            data: {!! json_encode($memberData) !!}
        }],
        chart: {
            type: 'area',
            height: 300,
            fontFamily: 'inherit',
            toolbar: { show: false }
        },
        colors: ['#0EA5E9'], // Tailwind sky-500
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.4,
                opacityTo: 0.05,
                stops: [0, 90, 100]
            }
        },
        dataLabels: { enabled: false },
        stroke: {
            curve: 'smooth',
            width: 3
        },
        xaxis: {
            categories: {!! json_encode($memberLabels) !!},
            axisBorder: { show: false },
            axisTicks: { show: false },
            labels: {
                style: { colors: '#6B7280', fontSize: '12px' }
            }
        },
        yaxis: {
            labels: {
                formatter: function (value) {
                    return Math.round(value);
                },
                style: { colors: '#6B7280', fontSize: '12px' }
            }
        },
        grid: {
            borderColor: '#F3F4F6',
            strokeDashArray: 4,
            yaxis: { lines: { show: true } }
        },
        tooltip: {
            theme: 'light'
        }
    };

    var growthChart = new ApexCharts(document.querySelector("#growth-chart"), growthOptions);
    growthChart.render();
});
</script>
@endpush
@endsection
