<div class="flex items-center justify-end mb-4">
    <form action="{{ route('admin.activity-logs.clear') }}" method="POST" onsubmit="return confirm('Are you sure you want to clear ALL activity logs? This action cannot be undone.');">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-red-600 border border-red-200 hover:bg-red-50 rounded-lg px-3 py-1.5 text-sm font-medium transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            Clear Activity Logs
        </button>
    </form>
</div>

<form action="{{ route('admin.settings.index') }}" method="GET" class="mb-5 bg-gray-50 p-4 rounded-xl border border-gray-100">
    <input type="hidden" name="activeTab" value="activity">
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
        
        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Search Description</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search logs..." class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
        </div>
        
        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">User</label>
            <select name="user_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                <option value="">All Users</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" @selected(request('user_id') == $user->id)>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        
        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Module</label>
            <select name="module" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                <option value="">All Modules</option>
                @foreach($modules as $module)
                    <option value="{{ $module }}" @selected(request('module') == $module)>{{ ucfirst($module) }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Action</label>
            <select name="action" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                <option value="">All Actions</option>
                @foreach($actions as $action)
                    <option value="{{ $action }}" @selected(request('action') == $action)>{{ ucfirst($action) }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex gap-2 h-10">
            <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg text-sm transition-colors flex items-center justify-center">
                Filter
            </button>
            @if(request()->hasAny(['search', 'user_id', 'module', 'action']) && request('activeTab') === 'activity')
            <a href="{{ route('admin.settings.index', ['activeTab' => 'activity']) }}" class="flex-none px-3 bg-white border border-gray-300 hover:bg-gray-50 text-gray-600 font-medium rounded-lg text-sm transition-colors flex items-center justify-center" title="Clear Filters">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </a>
            @endif
        </div>
    </div>
</form>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-500 uppercase text-xs font-semibold">
                <tr>
                    <th class="px-6 py-4">User</th>
                    <th class="px-6 py-4">Action</th>
                    <th class="px-6 py-4">Module</th>
                    <th class="px-6 py-4">Description</th>
                    <th class="px-6 py-4">IP</th>
                    <th class="px-6 py-4">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($logs as $log)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-3 font-medium text-gray-800">{{ $log->user->name ?? 'System' }}</td>
                    <td class="px-6 py-3">
                        @php
                            $actionLower = strtolower($log->action);
                            $badgeClass = 'bg-gray-100 text-gray-700';
                            if (str_contains($actionLower, 'create') || str_contains($actionLower, 'add')) {
                                $badgeClass = 'bg-emerald-50 text-emerald-700 border border-emerald-100';
                            } elseif (str_contains($actionLower, 'update') || str_contains($actionLower, 'edit')) {
                                $badgeClass = 'bg-blue-50 text-blue-700 border border-blue-100';
                            } elseif (str_contains($actionLower, 'delete') || str_contains($actionLower, 'remove')) {
                                $badgeClass = 'bg-rose-50 text-rose-700 border border-rose-100';
                            }
                        @endphp
                        <span class="px-2.5 py-1 {{ $badgeClass }} rounded-full text-xs font-semibold tracking-wide uppercase">{{ $log->action }}</span>
                    </td>
                    <td class="px-6 py-3 text-gray-500 font-medium">{{ $log->module }}</td>
                    <td class="px-6 py-3 text-gray-500 max-w-xs">
                        <p class="truncate" title="{{ $log->description }}">{{ $log->description ?? '—' }}</p>
                    </td>
                    <td class="px-6 py-3 font-mono text-xs text-gray-500">{{ $log->ip_address ?? '—' }}</td>
                    <td class="px-6 py-3 text-gray-400 whitespace-nowrap">{{ $log->created_at?->format('M d, Y H:i') }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400">No logs found matching your criteria.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($logs->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">{{ $logs->links() }}</div>
    @endif
</div>
