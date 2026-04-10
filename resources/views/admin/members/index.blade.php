@extends('layouts.admin')
@section('title', 'Members')

@section('content')
    <div class="flex items-center justify-between mb-2">
        <h1 class="text-xl font-bold text-gray-800">Members Management</h1>
    </div>

    <x-admin.filter-bar 
        :action="route('admin.members.index')" 
        searchPlaceholder="Search by name or email..."
        :sortable="['created_at' => 'Date Joined', 'full_name' => 'Name', 'member_code' => 'Member Code']">
        
        <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            <option value="">All Statuses</option>
            <option value="active" @selected(request('status') === 'active')>Active</option>
            <option value="suspended" @selected(request('status') === 'suspended')>Suspended</option>
            <option value="expired" @selected(request('status') === 'expired')>Expired</option>
        </select>
        
    </x-admin.filter-bar>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
            <tr>
                <th class="px-6 py-3">Member Code</th>
                <th class="px-6 py-3">Full Name</th>
                <th class="px-6 py-3">Email</th>
                <th class="px-6 py-3">Status</th>
                <th class="px-6 py-3">Subscription</th>
                <th class="px-6 py-3">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($members as $member)
            <tr class="border-t border-gray-100 hover:bg-gray-50">
                <td class="px-6 py-4 font-mono text-xs text-gray-700">{{ $member->member_code }}</td>
                <td class="px-6 py-4 font-medium text-gray-800">{{ $member->full_name ?: '—' }}</td>
                <td class="px-6 py-4 text-gray-500">{{ $member->user?->email }}</td>
                <td class="px-6 py-4">
                    @php $statusColors = ['active'=>'bg-green-100 text-green-700','suspended'=>'bg-red-100 text-red-700','expired'=>'bg-yellow-100 text-yellow-700']; @endphp
                    <span class="px-2 py-1 rounded text-xs font-medium {{ $statusColors[$member->status] ?? 'bg-gray-100 text-gray-600' }}">{{ $member->status }}</span>
                </td>
                <td class="px-6 py-4 text-gray-500">
                    @php $activeSub = $member->activeSubscription(); @endphp
                    @if($activeSub)
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-blue-100 text-blue-700">{{ $activeSub->plan->name }}</span>
                    @else
                        <span class="text-xs text-gray-400">None</span>
                    @endif
                </td>
                <td class="px-6 py-4 flex gap-2">
                    <a href="{{ route('admin.members.show', $member) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">View</a>
                    <a href="{{ route('admin.members.edit', $member) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm">Edit</a>
                    <form action="{{ route('admin.members.destroy', $member) }}" method="POST" onsubmit="return confirm('Delete member?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-6 py-8 text-center text-gray-400">No members found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100">{{ $members->links() }}</div>
</div>
@endsection
