@extends('layouts.admin')
@section('title', 'Members')
@section('content')
<div class="space-y-5">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Members</h1>
            <p class="text-gray-500 text-sm mt-1">View and manage registered library members</p>
        </div>
    </div>
    <x-admin.filter-bar :action="route('admin.members.index')" searchPlaceholder="Search by name or email..." :sortable="['created_at' => 'Date Joined', 'full_name' => 'Name', 'member_code' => 'Member Code']">
        <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            <option value="">All Statuses</option>
            <option value="active" @selected(request('status') === 'active')>Active</option>
            <option value="suspended" @selected(request('status') === 'suspended')>Suspended</option>
            <option value="expired" @selected(request('status') === 'expired')>Expired</option>
        </select>
    </x-admin.filter-bar>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Member Code</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Subscription</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($members as $member)
                @php
                    $statusDot  = ['active' => 'bg-green-500', 'suspended' => 'bg-red-500', 'expired' => 'bg-yellow-400'];
                    $statusText = ['active' => 'text-green-700 bg-green-100', 'suspended' => 'text-red-700 bg-red-100', 'expired' => 'text-yellow-700 bg-yellow-100'];
                    $activeSub  = $member->activeSubscription();
                    $planName   = $activeSub?->plan?->name ?? null;
                    $subColors  = [
                        'Basic'   => 'bg-blue-50 text-blue-700',
                        'Premium' => 'bg-purple-50 text-purple-700',
                    ];
                    $subClass  = $planName ? ($subColors[$planName] ?? 'bg-gray-100 text-gray-500') : 'bg-gray-100 text-gray-400';
                    $planLabel = $planName ?? 'No Plan';
                @endphp
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <code class="font-mono text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded">{{ $member->member_code }}</code>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-sm font-semibold flex-shrink-0">
                                {{ strtoupper(substr($member->first_name ?? '?', 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $member->first_name }} {{ $member->last_name }}</p>
                                <p class="text-xs text-gray-400">{{ $member->user?->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusText[$member->status] ?? 'bg-gray-100 text-gray-600' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $statusDot[$member->status] ?? 'bg-gray-400' }}"></span>
                            {{ ucfirst($member->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-xs font-medium px-2.5 py-0.5 rounded-full {{ $subClass }}">
                            {{ $planLabel }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            {{-- View --}}
                            <a href="{{ route('admin.members.show', $member) }}"
                               class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200"
                               title="View">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            {{-- Edit --}}
                            <a href="{{ route('admin.members.edit', $member) }}"
                               class="p-1.5 text-indigo-300 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all duration-200"
                               title="Edit">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            {{-- Delete --}}
                            <form action="{{ route('admin.members.destroy', $member) }}" method="POST"
                                  onsubmit="return confirm('Delete member?')" class="inline-block">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="p-1.5 text-rose-300 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all duration-200"
                                        title="Delete">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5">
                    <div class="text-center py-16">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <h3 class="text-gray-900 font-semibold mb-1">No members yet</h3>
                        <p class="text-gray-500 text-sm">Members will appear here after they register.</p>
                    </div>
                </td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100">{{ $members->links() }}</div>
    </div>
</div>
@endsection
