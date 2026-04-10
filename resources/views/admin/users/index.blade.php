@extends('layouts.admin')
@section('title', 'Users')

@section('content')

    <x-admin.filter-bar 
        :action="route('admin.users.index')" 
        searchPlaceholder="Search name or email..."
        :sortable="['created_at' => 'Date Joined', 'email' => 'Email Address']">
        
        <select name="role" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            <option value="">All Roles</option>
            <option value="admin" @selected(request('role') === 'admin')>Admin</option>
            <option value="member" @selected(request('role') === 'member')>Member</option>
        </select>
        
    </x-admin.filter-bar>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
            <tr>
                <th class="px-6 py-3">Full Name / Email</th>
                <th class="px-6 py-3">Email</th>
                <th class="px-6 py-3">Role</th>
                <th class="px-6 py-3">Member Code</th>
                <th class="px-6 py-3">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr class="border-t border-gray-100 hover:bg-gray-50">
                <td class="px-6 py-4 font-medium text-gray-800">{{ $user->member?->full_name ?: $user->email }}</td>
                <td class="px-6 py-4 text-gray-600">{{ $user->email }}</td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 rounded text-xs font-medium {{ $user->role === 'admin' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600' }}">
                        {{ $user->role }}
                    </span>
                </td>
                <td class="px-6 py-4 text-gray-500">{{ $user->member?->member_code ?? '—' }}</td>
                <td class="px-6 py-4 flex gap-2">
                    <a href="{{ route('admin.users.show', $user) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">View</a>
                    <a href="{{ route('admin.users.edit', $user) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm">Edit</a>
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Delete this user?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400">No users found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100">{{ $users->links() }}</div>
</div>
@endsection
