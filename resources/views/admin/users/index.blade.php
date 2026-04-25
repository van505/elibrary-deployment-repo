@extends('layouts.admin')
@section('title', 'Users')

@section('content')
<div class="space-y-5">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Users</h1>
            <p class="text-gray-500 text-sm mt-1">View and manage all registered users</p>
        </div>
    </div>

    <x-admin.filter-bar
        :action="route('admin.users.index')"
        searchPlaceholder="Search name or email..."
        :sortable="['created_at' => 'Date Joined', 'email' => 'Email Address']">

        <select name="role" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            <option value="">All Roles</option>
            <option value="admin"  @selected(request('role') === 'admin')>Admin</option>
            <option value="member" @selected(request('role') === 'member')>Member</option>
        </select>

    </x-admin.filter-bar>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($users as $user)
                @php
                    $roleColors = [
                        'admin'  => 'bg-purple-100 text-purple-700',
                        'member' => 'bg-blue-100 text-blue-700',
                    ];
                    $roleClass = $roleColors[$user->role] ?? 'bg-gray-100 text-gray-500';
                @endphp
                <tr class="hover:bg-gray-50 transition-colors">
                    {{-- Combined User cell --}}
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center text-sm font-semibold flex-shrink-0">
                                {{ strtoupper(substr($user->first_name ?? $user->email, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $user->first_name }} {{ $user->last_name }}</p>
                                <p class="text-xs text-gray-400">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    {{-- Role pill --}}
                    <td class="px-6 py-4">
                        <span class="text-xs font-medium px-2.5 py-0.5 rounded-full {{ $roleClass }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    {{-- Joined date --}}
                    <td class="px-6 py-4 text-xs text-gray-400">
                        {{ $user->created_at?->format('M d, Y') ?? '—' }}
                    </td>
                    {{-- Actions --}}
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            {{-- View --}}
                            <a href="{{ route('admin.users.show', $user) }}"
                               class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200"
                               title="View">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            {{-- Edit --}}
                            <a href="{{ route('admin.users.edit', $user) }}"
                               class="p-1.5 text-indigo-300 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all duration-200"
                               title="Edit">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            {{-- Delete --}}
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                  onsubmit="return confirm('Delete this user?')" class="inline-block">
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
                <tr><td colspan="4" class="px-6 py-8 text-center text-gray-400">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100">{{ $users->links() }}</div>
    </div>
</div>
@endsection
