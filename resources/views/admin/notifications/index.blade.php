@extends('layouts.admin')
@section('title', 'Notifications')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Notifications</h1>
        
        <form action="{{ route('admin.notifications.mark-all-read') }}" method="POST">
            @csrf
            <button type="submit" class="bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 hover:text-indigo-600 px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Mark All as Read
            </button>
        </form>
    </div>

    {{-- Filter Tabs --}}
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.notifications.index', ['filter' => 'all']) }}" 
           class="px-4 py-1.5 rounded-full text-sm font-medium transition-colors {{ $filter === 'all' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200' }}">
            All
        </a>
        <a href="{{ route('admin.notifications.index', ['filter' => 'unread']) }}" 
           class="px-4 py-1.5 rounded-full text-sm font-medium transition-colors {{ $filter === 'unread' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200' }}">
            Unread
        </a>
        <a href="{{ route('admin.notifications.index', ['filter' => 'read']) }}" 
           class="px-4 py-1.5 rounded-full text-sm font-medium transition-colors {{ $filter === 'read' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200' }}">
            Read
        </a>
    </div>

    {{-- Notifications List --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        @if($notifications->count() > 0)
            <div class="divide-y divide-gray-100">
                @foreach($notifications as $notification)
                    <div class="px-6 py-5 flex items-start gap-4 transition-colors {{ $notification->is_read ? 'bg-white hover:bg-gray-50' : 'bg-indigo-50/30 hover:bg-indigo-50/50' }}">
                        
                        {{-- Icon --}}
                        @php
                            $iconBg = 'bg-gray-100'; $iconColor = 'text-gray-500'; 
                            $iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />';
                            
                            if ($notification->type === 'new_member') {
                                $iconBg = 'bg-emerald-100'; $iconColor = 'text-emerald-600'; 
                                $iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />';
                            } elseif ($notification->type === 'new_review') {
                                $iconBg = 'bg-amber-100'; $iconColor = 'text-amber-600';
                                $iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />';
                            } elseif ($notification->type === 'new_purchase') {
                                $iconBg = 'bg-blue-100'; $iconColor = 'text-blue-600';
                                $iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />';
                            } elseif ($notification->type === 'expiry_warning') {
                                $iconBg = 'bg-orange-100'; $iconColor = 'text-orange-600';
                                $iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />';
                            }
                        @endphp
                        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 mt-1 {{ $iconBg }} {{ $iconColor }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $iconSvg !!}</svg>
                        </div>
                        
                        {{-- Content --}}
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 leading-snug mb-1">{{ $notification->message }}</p>
                            <div class="flex items-center gap-3 text-xs text-gray-500">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                                <span>&bull;</span>
                                <span>{{ $notification->created_at->format('M d, Y h:i A') }}</span>
                                <span>&bull;</span>
                                @if($notification->is_read)
                                    <span class="text-gray-400 font-medium">Read</span>
                                @else
                                    <span class="text-indigo-600 font-bold">Unread</span>
                                @endif
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center gap-2 ml-4">
                            @if(!$notification->is_read)
                                <form action="{{ route('admin.notifications.mark-as-read', $notification->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-indigo-600 rounded-lg transition-colors focus:outline-none" title="Mark as Read">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </button>
                                </form>
                            @endif
                            <form action="{{ route('admin.notifications.destroy', $notification->id) }}" method="POST" onsubmit="return confirm('Delete this notification?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 rounded-lg transition-colors focus:outline-none" title="Delete">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
            
            @if($notifications->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/30">
                    {{ $notifications->links() }}
                </div>
            @endif
        @else
            <div class="px-6 py-16 text-center">
                <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 border border-gray-100">
                    <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                </div>
                <p class="text-gray-900 font-medium mb-1">All caught up!</p>
                <p class="text-gray-400 text-sm">You have no {{ $filter !== 'all' ? $filter : '' }} notifications at this time.</p>
            </div>
        @endif
    </div>
</div>
@endsection
