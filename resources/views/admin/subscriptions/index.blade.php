@extends('layouts.admin')
@section('title', 'Subscriptions')

@section('content')
<div class="space-y-5">

    <x-admin.filter-bar 
        :action="route('admin.subscriptions.index')" 
        searchPlaceholder="Search member name..."
        :sortable="['created_at' => 'Date Subscribed', 'started_at' => 'Date Started', 'expires_at' => 'Expiry Date']">
        
        <select name="plan_id" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            <option value="">All Plans</option>
            @foreach($plans as $plan)
                <option value="{{ $plan->id }}" @selected(request('plan_id') == $plan->id)>{{ $plan->name }}</option>
            @endforeach
        </select>
        
        <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            <option value="">All Statuses</option>
            <option value="active" @selected(request('status') === 'active')>Active</option>
            <option value="expired" @selected(request('status') === 'expired')>Expired</option>
            <option value="cancelled" @selected(request('status') === 'cancelled')>Cancelled</option>
        </select>
        
    </x-admin.filter-bar>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left font-semibold text-gray-600 px-6 py-3">Member</th>
                    <th class="text-left font-semibold text-gray-600 px-6 py-3">Plan</th>
                    <th class="text-left font-semibold text-gray-600 px-6 py-3">Status</th>
                    <th class="text-left font-semibold text-gray-600 px-6 py-3">Started</th>
                    <th class="text-left font-semibold text-gray-600 px-6 py-3">Expires</th>
                    <th class="text-left font-semibold text-gray-600 px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($subscriptions as $sub)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <p class="font-medium text-gray-800">{{ $sub->member?->full_name ?: ($sub->member?->user?->email ?? 'N/A') }}</p>
                        <p class="text-xs text-gray-400">{{ $sub->member?->member_code ?? '' }}</p>
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-700">{{ $sub->plan->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4">
                        @php $statusColors = ['active'=>'bg-green-100 text-green-700','expired'=>'bg-red-100 text-red-700','cancelled'=>'bg-gray-100 text-gray-600']; @endphp
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $statusColors[$sub->status] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ ucfirst($sub->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ $sub->started_at?->format('M d, Y') ?? '—' }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $sub->expires_at?->format('M d, Y') ?? 'Never' }}</td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.subscriptions.show', $sub) }}" class="text-blue-600 hover:underline text-xs">View</a>
                            <form action="{{ route('admin.subscriptions.update', $sub) }}" method="POST">
                                @csrf @method('PUT')
                                <select name="status" onchange="this.form.submit()" class="text-xs border border-gray-300 rounded px-2 py-1 focus:outline-none">
                                    <option value="active"    @selected($sub->status === 'active')>Active</option>
                                    <option value="expired"   @selected($sub->status === 'expired')>Expired</option>
                                    <option value="cancelled" @selected($sub->status === 'cancelled')>Cancelled</option>
                                </select>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-10 text-center text-gray-400">No subscriptions found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100">{{ $subscriptions->links() }}</div>
    </div>
</div>
@endsection
