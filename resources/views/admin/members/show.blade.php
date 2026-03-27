@extends('layouts.admin')
@section('title', 'Member Details')

@section('content')
<div class="mb-6 flex flex-wrap justify-between items-center gap-3">
    <a href="{{ route('admin.members.index') }}" class="text-blue-600 hover:underline text-sm">← Back to Members</a>
    <div class="flex gap-2">
        {{-- Status Toggle --}}
        <form action="{{ route('admin.members.toggle-status', $member->id) }}" method="POST">
            @csrf
            <button type="submit"
                    class="px-4 py-2 rounded-lg text-sm font-medium {{ $member->status === 'active' ? 'bg-red-100 text-red-700 hover:bg-red-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }}">
                {{ $member->status === 'active' ? '⛔ Suspend Member' : '✅ Activate Member' }}
            </button>
        </form>
        <a href="{{ route('admin.members.edit', $member) }}"
           class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm">Edit</a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    {{-- Member Info --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-bold text-gray-800 mb-4">Member Info</h3>
        <dl class="space-y-3 text-sm">
            <div><dt class="text-gray-500">Full Name</dt><dd class="font-medium">{{ $member->full_name ?: '—' }}</dd></div>
            <div><dt class="text-gray-500">Email</dt><dd>{{ $member->user?->email }}</dd></div>
            <div><dt class="text-gray-500">Code</dt><dd class="font-mono text-xs">{{ $member->member_code }}</dd></div>
            <div><dt class="text-gray-500">Phone</dt><dd>{{ $member->phone ?? '—' }}</dd></div>
            <div><dt class="text-gray-500">Address</dt><dd>{{ $member->address ?? '—' }}</dd></div>
            <div><dt class="text-gray-500">Status</dt><dd>
                @php $sc=['active'=>'bg-green-100 text-green-700','suspended'=>'bg-red-100 text-red-700','expired'=>'bg-gray-100 text-gray-600']; @endphp
                <span class="px-2 py-0.5 rounded text-xs font-medium {{ $sc[$member->status] ?? '' }}">{{ ucfirst($member->status) }}</span>
            </dd></div>
            <div><dt class="text-gray-500">Joined</dt><dd class="text-xs">{{ $member->created_at->format('M d, Y') }}</dd></div>
        </dl>
    </div>

    <div class="lg:col-span-2 space-y-6">
        {{-- Active Subscription --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 font-semibold text-gray-800">Current Subscription</div>
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
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-3 text-left">Plan</th>
                            <th class="px-6 py-3 text-left">Started</th>
                            <th class="px-6 py-3 text-left">Expires</th>
                            <th class="px-6 py-3 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($member->subscriptions as $sub)
                        <tr class="border-t border-gray-100 hover:bg-gray-50">
                            <td class="px-6 py-3 font-medium">{{ $sub->plan->name }}</td>
                            <td class="px-6 py-3 text-gray-400">{{ $sub->started_at?->format('M d, Y') }}</td>
                            <td class="px-6 py-3 text-gray-400">{{ $sub->expires_at?->format('M d, Y') ?? 'Never' }}</td>
                            <td class="px-6 py-3">
                                @php $sc=['active'=>'bg-green-100 text-green-700','expired'=>'bg-gray-100 text-gray-500','cancelled'=>'bg-red-100 text-red-600']; @endphp
                                <span class="px-2 py-0.5 rounded text-xs {{ $sc[$sub->status] ?? '' }}">{{ ucfirst($sub->status) }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-6 py-4 text-center text-gray-400">No subscriptions found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Reading History --}}
<div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <span class="font-semibold text-gray-800">Reading History</span>
        <span class="text-xs text-gray-400">{{ $member->ebookAccess->count() }} total</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3 text-left">Ebook</th>
                    <th class="px-6 py-3 text-left">Authors</th>
                    <th class="px-6 py-3 text-left">Accessed</th>
                </tr>
            </thead>
            <tbody>
                @forelse($member->ebookAccess->take(15) as $access)
                <tr class="border-t border-gray-100 hover:bg-gray-50">
                    <td class="px-6 py-3 font-medium">
                        <a href="{{ route('admin.ebooks.show', $access->ebook->id) }}" class="hover:text-blue-600">
                            {{ $access->ebook->title ?? '—' }}
                        </a>
                    </td>
                    <td class="px-6 py-3 text-gray-400">{{ $access->ebook->authors->pluck('full_name')->join(', ') ?: '—' }}</td>
                    <td class="px-6 py-3 text-gray-400">{{ $access->accessed_at?->format('M d, Y') ?? $access->created_at->format('M d, Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="3" class="px-6 py-6 text-center text-gray-400">No reading history.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Member Reviews --}}
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <span class="font-semibold text-gray-800">Reviews</span>
        <span class="text-xs text-gray-400">{{ $member->reviews->count() }} total</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3 text-left">Ebook</th>
                    <th class="px-6 py-3 text-left">Rating</th>
                    <th class="px-6 py-3 text-left">Comment</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-left">Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($member->reviews as $review)
                <tr class="border-t border-gray-100 hover:bg-gray-50">
                    <td class="px-6 py-3 font-medium">{{ $review->ebook->title ?? '—' }}</td>
                    <td class="px-6 py-3">
                        <span class="text-yellow-500">{{ str_repeat('★', $review->rating) }}</span><span class="text-gray-200">{{ str_repeat('★', 5 - $review->rating) }}</span>
                    </td>
                    <td class="px-6 py-3 text-gray-500 max-w-xs truncate">{{ $review->comment ?? '—' }}</td>
                    <td class="px-6 py-3">
                        @php $sc=['pending'=>'bg-yellow-100 text-yellow-700','approved'=>'bg-green-100 text-green-700','rejected'=>'bg-red-100 text-red-700']; @endphp
                        <span class="px-2 py-0.5 rounded text-xs font-medium {{ $sc[$review->status] ?? '' }}">{{ ucfirst($review->status) }}</span>
                    </td>
                    <td class="px-6 py-3 text-gray-400">{{ $review->created_at->format('M d, Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-6 text-center text-gray-400">No reviews submitted.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
