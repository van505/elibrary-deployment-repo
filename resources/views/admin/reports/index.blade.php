@extends('layouts.admin')
@section('title', 'Analytics & Reports')

@push('styles')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
@endpush

@section('content')
<div class="space-y-6">

    {{-- Overview Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <p class="text-sm text-gray-500 mb-1">Accesses This Month</p>
            <p class="text-3xl font-bold text-gray-800">{{ $accessesThisMonth }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <p class="text-sm text-gray-500 mb-1">Total Reviews</p>
            <p class="text-3xl font-bold text-gray-800">{{ $totalReviews }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <p class="text-sm text-gray-500 mb-1">Approved Reviews</p>
            <p class="text-3xl font-bold text-gray-800">{{ $approvedReviews }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <p class="text-sm text-gray-500 mb-1">Review Approval Rate</p>
            <p class="text-3xl font-bold {{ $approvalRate >= 70 ? 'text-green-600' : 'text-yellow-600' }}">{{ $approvalRate }}%</p>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Revenue Bar Chart --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-semibold text-gray-800 mb-4">Revenue – Last 6 Months</h2>
            <canvas id="revenueChart" height="200"></canvas>
        </div>

        {{-- Member Growth Line Chart --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-semibold text-gray-800 mb-4">Member Growth – Last 6 Months</h2>
            <canvas id="memberChart" height="200"></canvas>
        </div>
    </div>

    {{-- Top Ebooks & Members Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Top 5 Ebooks --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 font-semibold text-gray-800">Most Read Ebooks</div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3 text-left">#</th>
                        <th class="px-6 py-3 text-left">Title</th>
                        <th class="px-6 py-3 text-right">Accesses</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topEbooks as $access)
                    <tr class="border-t border-gray-100 hover:bg-gray-50">
                        <td class="px-6 py-3 text-gray-400">{{ $loop->iteration }}</td>
                        <td class="px-6 py-3">
                            <a href="{{ route('admin.ebooks.show', $access->ebook_id) }}" class="text-gray-800 hover:text-blue-600 font-medium">
                                {{ $access->ebook->title ?? '—' }}
                            </a>
                            <p class="text-xs text-gray-400">{{ $access->ebook->authors->pluck('full_name')->join(', ') ?: '—' }}</p>
                        </td>
                        <td class="px-6 py-3 text-right font-semibold text-blue-600">{{ $access->access_count }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="px-6 py-6 text-center text-gray-400">No data yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Top 5 Members --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 font-semibold text-gray-800">Most Active Members</div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3 text-left">#</th>
                        <th class="px-6 py-3 text-left">Member</th>
                        <th class="px-6 py-3 text-right">Accesses</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topMembers as $access)
                    <tr class="border-t border-gray-100 hover:bg-gray-50">
                        <td class="px-6 py-3 text-gray-400">{{ $loop->iteration }}</td>
                        <td class="px-6 py-3">
                            <a href="{{ route('admin.members.show', $access->member_id) }}" class="text-gray-800 hover:text-blue-600 font-medium">
                                {{ $access->member?->full_name ?: '—' }}
                            </a>
                            <p class="text-xs text-gray-400">{{ $access->member?->member_code }}</p>
                        </td>
                        <td class="px-6 py-3 text-right font-semibold text-green-600">{{ $access->access_count }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="px-6 py-6 text-center text-gray-400">No data yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

@push('scripts')
<script>
// Revenue Bar Chart
new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($revenueLabels) !!},
        datasets: [{
            label: 'Revenue (₱)',
            data: {!! json_encode($revenueData) !!},
            backgroundColor: 'rgba(59,130,246,0.7)',
            borderColor: 'rgba(59,130,246,1)',
            borderWidth: 1,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { callback: v => '₱' + v.toLocaleString() } }
        }
    }
});

// Member Growth Line Chart
new Chart(document.getElementById('memberChart'), {
    type: 'line',
    data: {
        labels: {!! json_encode($memberLabels) !!},
        datasets: [{
            label: 'New Members',
            data: {!! json_encode($memberData) !!},
            borderColor: 'rgba(16,185,129,1)',
            backgroundColor: 'rgba(16,185,129,0.1)',
            borderWidth: 2,
            tension: 0.4,
            fill: true,
            pointBackgroundColor: 'rgba(16,185,129,1)',
            pointRadius: 4,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
});
</script>
@endpush
@endsection
