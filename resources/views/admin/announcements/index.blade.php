@extends('layouts.admin')
@section('title', 'Announcements')

@section('content')
<div class="space-y-5">

    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Announcements</h1>
            <p class="text-gray-500 text-sm mt-1">Manage system-wide announcements for members</p>
        </div>
        <a href="{{ route('admin.announcements.create') }}"
           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg transition-colors shadow-sm text-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            New Announcement
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Active Period</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($announcements as $announcement)
                    @php
                        $typeColors = [
                            'info'    => 'bg-blue-50 text-blue-700',
                            'warning' => 'bg-yellow-50 text-yellow-700',
                            'success' => 'bg-green-50 text-green-700',
                            'danger'  => 'bg-red-50 text-red-700',
                        ];
                    @endphp
                    <tr class="hover:bg-gray-50/60 transition-colors duration-200">
                        {{-- Title + message preview --}}
                        <td class="px-4 py-4 max-w-xs">
                            <p class="font-semibold text-gray-900">{{ $announcement->title }}</p>
                            <p class="text-xs text-gray-500 mt-0.5 line-clamp-1">{{ $announcement->message }}</p>
                        </td>
                        {{-- Type badge --}}
                        <td class="px-4 py-4">
                            <span class="px-2 py-0.5 rounded-md text-xs font-medium uppercase tracking-wide {{ $typeColors[$announcement->type] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ $announcement->type }}
                            </span>
                        </td>
                        {{-- Status pill --}}
                        <td class="px-4 py-4">
                            @if($announcement->is_active)
                                <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span> Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 bg-gray-100 text-gray-500 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400 inline-block"></span> Inactive
                                </span>
                            @endif
                        </td>
                        {{-- Active period --}}
                        <td class="px-4 py-4 text-xs text-gray-500">
                            @if($announcement->starts_at || $announcement->ends_at)
                                <div class="flex flex-col gap-1">
                                    <span class="text-green-600">{{ $announcement->starts_at ? $announcement->starts_at->format('M d, Y g:ia') : 'Now' }}</span>
                                    <span class="text-gray-300 leading-none">↓</span>
                                    <span class="text-red-500">{{ $announcement->ends_at ? $announcement->ends_at->format('M d, Y g:ia') : 'Forever' }}</span>
                                </div>
                            @else
                                <span class="italic text-gray-400">Always</span>
                            @endif
                        </td>
                        {{-- Icon-only actions --}}
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-1">
                                {{-- Edit --}}
                                <a href="{{ route('admin.announcements.edit', $announcement) }}"
                                   class="p-1.5 text-indigo-500 hover:text-indigo-700 rounded-lg transition-colors duration-200" title="Edit">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                {{-- Delete --}}
                                <form action="{{ route('admin.announcements.destroy', $announcement) }}" method="POST"
                                      onsubmit="return confirm('Delete this announcement?')" class="inline-block">
                                    @csrf @method('DELETE')
                                    <button type="submit" title="Delete"
                                            class="p-1.5 text-rose-500 hover:text-rose-700 rounded-lg transition-colors duration-200">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                                </svg>
                                <p class="text-gray-400 font-medium text-sm">No announcements published yet.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($announcements->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">{{ $announcements->links() }}</div>
        @endif
    </div>
</div>
@endsection
