@extends('layouts.admin')
@section('title', 'Member Details')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <a href="{{ route('admin.members.index') }}" class="text-blue-600 hover:underline text-sm">← Back</a>
    <a href="{{ route('admin.members.edit', $member) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm">Edit</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-bold text-gray-800 mb-4">Member Info</h3>
        <dl class="space-y-3 text-sm">
            <div><dt class="text-gray-500">Full Name</dt><dd class="font-medium">{{ $member->full_name ?: '—' }}</dd></div>
            <div><dt class="text-gray-500">Email</dt><dd>{{ $member->user?->email }}</dd></div>
            <div><dt class="text-gray-500">Code</dt><dd class="font-mono text-xs">{{ $member->member_code }}</dd></div>
            <div><dt class="text-gray-500">Phone</dt><dd>{{ $member->phone ?? '—' }}</dd></div>
            <div><dt class="text-gray-500">Status</dt><dd><span class="px-2 py-0.5 rounded text-xs {{ $member->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ $member->status }}</span></dd></div>
            <div><dt class="text-gray-500">Address</dt><dd>{{ $member->address ?? '—' }}</dd></div>
        </dl>
    </div>
    <div class="lg:col-span-2 space-y-6">
        {{-- Active Subscription --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 font-semibold text-gray-800">Subscription</div>
            @php $activeSub = $member->activeSubscription(); @endphp
            @if($activeSub)
            <div class="px-6 py-4 text-sm space-y-1">
                <p class="font-medium text-gray-800">{{ $activeSub->plan->name }} Plan</p>
                <p class="text-gray-500">Status: <span class="font-medium">{{ $activeSub->status }}</span></p>
                <p class="text-gray-500">Started: {{ $activeSub->started_at?->format('M d, Y') ?? '—' }}</p>
                <p class="text-gray-500">Expires: {{ $activeSub->expires_at?->format('M d, Y') ?? 'Never' }}</p>
            </div>
            @else
            <div class="px-6 py-4 text-center text-gray-400 text-sm">No active subscription.</div>
            @endif
        </div>
        {{-- Subscription History --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 font-semibold text-gray-800">Subscription History</div>
            @forelse($member->subscriptions as $sub)
            <div class="px-6 py-3 border-t border-gray-100 flex justify-between text-sm">
                <span class="font-medium">{{ $sub->plan->name }}</span>
                <span class="text-gray-400 text-xs">{{ $sub->started_at?->format('M d, Y') }}</span>
                <span class="px-2 py-0.5 rounded text-xs bg-gray-100 text-gray-600">{{ $sub->status }}</span>
            </div>
            @empty
            <div class="px-6 py-4 text-center text-gray-400 text-sm">No subscriptions found.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
