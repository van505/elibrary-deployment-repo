@extends('layouts.admin')
@section('title', 'Analytics & Reports')

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
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 flex flex-col justify-center">
            <p class="text-sm font-medium text-gray-500 mb-1">Accesses This Month</p>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($accessesThisMonth) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 flex flex-col justify-center">
            <p class="text-sm font-medium text-gray-500 mb-1">Total Reviews</p>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($totalReviews) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 flex flex-col justify-center">
            <p class="text-sm font-medium text-gray-500 mb-1">Approved Reviews</p>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($approvedReviews) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 flex flex-col justify-center">
            <p class="text-sm font-medium text-gray-500 mb-1">Review Approval Rate</p>
            <p class="text-3xl font-bold {{ $approvalRate >= 70 ? 'text-emerald-600' : 'text-amber-500' }}">{{ $approvalRate }}%</p>
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
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-semibold">
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
                                <span class="inline-flex items-center justify-center px-2.5 py-1 text-xs font-bold rounded-full bg-indigo-50 text-indigo-700">
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
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-semibold">
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
                                <span class="inline-flex items-center justify-center px-2.5 py-1 text-xs font-bold rounded-full bg-emerald-50 text-emerald-700">
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
        colors: ['#10B981'], // Tailwind emerald-500
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
