@extends('layouts.admin')
@section('title', 'User Details')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:underline text-sm">← Back to Users</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-bold text-gray-800 mb-4">User Info</h3>
        <dl class="space-y-3 text-sm">
            <div><dt class="text-gray-500">Name</dt><dd class="font-medium">{{ $user->name }}</dd></div>
            <div><dt class="text-gray-500">Email</dt><dd>{{ $user->email }}</dd></div>
            <div><dt class="text-gray-500">Role</dt><dd><span class="px-2 py-1 rounded text-xs font-medium {{ $user->role === 'admin' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600' }}">{{ $user->role }}</span></dd></div>
            <div><dt class="text-gray-500">Joined</dt><dd>{{ $user->created_at->format('M d, Y') }}</dd></div>
        </dl>
        <a href="{{ route('admin.users.edit', $user) }}" class="mt-4 inline-block bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm">Edit User</a>
    </div>

    @if($user->member)
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-bold text-gray-800 mb-4">Member Details</h3>
        <dl class="grid grid-cols-2 gap-4 text-sm">
            <div><dt class="text-gray-500">Member Code</dt><dd class="font-medium">{{ $user->member->member_code }}</dd></div>
            <div><dt class="text-gray-500">Status</dt><dd>{{ $user->member->status }}</dd></div>
            <div><dt class="text-gray-500">Phone</dt><dd>{{ $user->member->phone ?? '—' }}</dd></div>
            <div><dt class="text-gray-500">Expiry</dt><dd>{{ $user->member->membership_expiry?->format('M d, Y') ?? '—' }}</dd></div>
            <div class="col-span-2"><dt class="text-gray-500">Address</dt><dd>{{ $user->member->address ?? '—' }}</dd></div>
        </dl>
    </div>
    @endif
</div>
@endsection
