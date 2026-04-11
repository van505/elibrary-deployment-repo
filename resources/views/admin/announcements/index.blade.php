@extends('layouts.admin')
@section('title', 'Manage Announcements')

@section('content')
<div class="mb-6 flex justify-between items-center gap-4">
    <h2 class="text-2xl font-bold text-gray-800">System Announcements</h2>
    <a href="{{ route('admin.announcements.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg transition-colors flex items-center gap-2 shadow-sm text-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        New Announcement
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                    <th class="px-6 py-4 font-semibold">Title</th>
                    <th class="px-6 py-4 font-semibold">Type</th>
                    <th class="px-6 py-4 font-semibold text-center">Status</th>
                    <th class="px-6 py-4 font-semibold text-center">Active Period</th>
                    <th class="px-6 py-4 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-100">
                @forelse($announcements as $announcement)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-800">{{ $announcement->title }}</p>
                            <p class="text-xs text-gray-400 mt-0.5 truncate max-w-xs">{{ $announcement->message }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $typeColors = [
                                    'info' => 'bg-blue-50 text-blue-700',
                                    'warning' => 'bg-yellow-50 text-yellow-700',
                                    'success' => 'bg-green-50 text-green-700',
                                    'danger' => 'bg-red-50 text-red-700',
                                ];
                            @endphp
                            <span class="px-2.5 py-1 rounded-md text-xs font-bold uppercase tracking-wider {{ $typeColors[$announcement->type] ?? 'bg-gray-100' }}">
                                {{ $announcement->type }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($announcement->is_active)
                                <span class="bg-emerald-100 text-emerald-700 text-xs font-bold px-2 py-1 rounded-full inline-flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active</span>
                            @else
                                <span class="bg-gray-100 text-gray-500 text-xs font-bold px-2 py-1 rounded-full">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center text-xs text-gray-500">
                            @if($announcement->starts_at || $announcement->ends_at)
                                <div class="flex flex-col gap-1 items-center">
                                    <span class="text-green-600">{{ $announcement->starts_at ? $announcement->starts_at->format('M d, y g:ia') : 'Now' }}</span>
                                    <span class="text-gray-300">↓</span>
                                    <span class="text-red-500">{{ $announcement->ends_at ? $announcement->ends_at->format('M d, y g:ia') : 'Forever' }}</span>
                                </div>
                            @else
                                <span class="italic text-gray-400">Always</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-3">
                                <a href="{{ route('admin.announcements.edit', $announcement) }}" class="text-yellow-600 hover:text-yellow-800 text-xs font-medium">Edit</a>
                                <form action="{{ route('admin.announcements.destroy', $announcement) }}" method="POST" onsubmit="return confirm('Delete this announcement?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-medium">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                                <p class="text-gray-400 font-medium">No announcements published yet.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($announcements->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $announcements->links() }}
        </div>
    @endif
</div>
@endsection
