@extends('layouts.admin')
@section('title', 'Activity Logs')

@section('content')
<h2 class="text-2xl font-bold text-gray-800 mb-6">Activity Logs</h2>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex gap-3">
        <form method="GET" class="flex gap-3">
            <input type="text" name="module" value="{{ request('module') }}" placeholder="Filter by module…" class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <input type="text" name="action" value="{{ request('action') }}" placeholder="Filter by action…" class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1.5 rounded-lg text-sm">Filter</button>
            <a href="{{ route('admin.activity-logs.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-600 px-4 py-1.5 rounded-lg text-sm">Clear</a>
        </form>
    </div>
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
