@extends('layouts.admin')
@section('title', 'Activity Logs')

@section('content')
    <div class="flex items-center justify-end mb-2">
        <form action="{{ route('admin.activity-logs.clear') }}" method="POST" onsubmit="return confirm('Are you sure you want to clear ALL activity logs? This action cannot be undone.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 px-4 py-1.5 rounded-lg text-sm font-medium transition-colors focus:ring-2 focus:ring-red-500 focus:outline-none flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                Clear Activity Logs
            </button>
        </form>
    </div>

    <x-admin.filter-bar 
        :action="route('admin.activity-logs.index')" 
        searchPlaceholder="Search description..."
        :sortable="['created_at' => 'Date Logged']">
        
        <select name="user_id" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none max-w-xs">
            <option value="">All Users</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" @selected(request('user_id') == $user->id)>{{ $user->name }} ({{ $user->email }})</option>
            @endforeach
        </select>
        
        <select name="module" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            <option value="">All Modules</option>
            @foreach($modules as $module)
                <option value="{{ $module }}" @selected(request('module') == $module)>{{ ucfirst($module) }}</option>
            @endforeach
        </select>

        <select name="action" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            <option value="">All Actions</option>
            @foreach($actions as $action)
                <option value="{{ $action }}" @selected(request('action') == $action)>{{ ucfirst($action) }}</option>
            @endforeach
        </select>
        
    </x-admin.filter-bar>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
            <tr>
                <th class="px-6 py-3">User</th>
                <th class="px-6 py-3">Action</th>
                <th class="px-6 py-3">Module</th>
                <th class="px-6 py-3">Description</th>
                <th class="px-6 py-3">IP</th>
                <th class="px-6 py-3">Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
            <tr class="border-t border-gray-100 hover:bg-gray-50">
                <td class="px-6 py-3">{{ $log->user->name ?? 'System' }}</td>
                <td class="px-6 py-3"><span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded text-xs">{{ $log->action }}</span></td>
                <td class="px-6 py-3 text-gray-500">{{ $log->module }}</td>
                <td class="px-6 py-3 text-gray-500 max-w-xs truncate">{{ $log->description ?? '—' }}</td>
                <td class="px-6 py-3 font-mono text-xs text-gray-400">{{ $log->ip_address ?? '—' }}</td>
                <td class="px-6 py-3 text-gray-400">{{ $log->created_at?->format('M d, Y H:i') }}</td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-6 py-8 text-center text-gray-400">No logs found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100">{{ $logs->links() }}</div>
</div>
@endsection
