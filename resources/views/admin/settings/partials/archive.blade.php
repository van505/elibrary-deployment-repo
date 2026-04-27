<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
    {{-- Entity Filters --}}
    @php
        $tabs = [
            'members'            => 'Members',
            'ebooks'             => 'Ebooks',
            'authors'            => 'Authors',
            'categories'         => 'Categories',
            'reviews'            => 'Reviews',
            'subscription-plans' => 'Sub Plans',
        ];
    @endphp
    <div class="flex flex-wrap gap-2">
        @foreach($tabs as $key => $label)
        <a href="{{ route('admin.settings.index', ['type' => $key, 'activeTab' => 'archive']) }}"
           class="flex items-center transition-colors {{ $type === $key ? 'bg-indigo-600 text-white rounded-full px-4 py-1.5 text-sm font-medium shadow-sm' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50 rounded-full px-4 py-1.5 text-sm font-medium' }}">
            {{ $label }}
            @if(($counts[$key] ?? 0) > 0)
                <span class="bg-red-500 text-white text-xs rounded-full px-2 py-0.5 ml-2 font-bold shadow-sm">
                    {{ $counts[$key] }}
                </span>
            @endif
        </a>
        @endforeach
    </div>

    {{-- Search Filter --}}
    <form action="{{ route('admin.settings.index') }}" method="GET" class="flex-shrink-0 w-full md:w-64 relative">
        <input type="hidden" name="type" value="{{ $type }}">
        <input type="hidden" name="activeTab" value="archive">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search archived record..." class="w-full border border-gray-300 rounded-lg pl-9 pr-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-shadow">
    </form>
</div>

{{-- Records Table --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-500 uppercase text-xs font-semibold">
                <tr>
                    <th class="px-6 py-4">Record</th>
                    <th class="px-6 py-4">Details</th>
                    <th class="px-6 py-4">Archived On</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($records as $record)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-bold text-gray-900">
                        @switch($type)
                            @case('members')
                                {{ $record->full_name ?: ('Member #' . $record->id) }}
                                @break
                            @case('ebooks')
                                {{ $record->title }}
                                @break
                            @case('authors')
                                {{ $record->full_name }}
                                @break
                            @case('categories')
                                {{ $record->name }}
                                @break
                            @case('reviews')
                                Review #{{ $record->id }} — Rating: {{ $record->rating }}/5
                                @break
                            @case('subscription-plans')
                                {{ $record->name }}
                                @break
                        @endswitch
                    </td>
                    <td class="px-6 py-4 text-gray-500 text-xs">
                        @switch($type)
                            @case('members')
                                Code: <span class="font-mono text-gray-400">{{ $record->member_code }}</span> | Status: {{ $record->status }}
                                @break
                            @case('ebooks')
                                ISBN: {{ $record->isbn ?? '—' }} | {{ ucfirst($record->access_level) }}
                                @break
                            @case('authors')
                                {{ $record->nationality ?? '—' }}
                                @break
                            @case('categories')
                                <p class="truncate max-w-xs" title="{{ $record->description }}">{{ $record->description ?? '—' }}</p>
                                @break
                            @case('reviews')
                                Status: {{ ucfirst($record->status) }}
                                @break
                            @case('subscription-plans')
                                ₱{{ number_format($record->price, 2) }} / plan
                                @break
                        @endswitch
                    </td>
                    <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                        {{ $record->deleted_at->format('M d, Y h:i A') }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-3">
                            {{-- Restore --}}
                            <form action="{{ route('admin.archive.restore', [$type, $record->id]) }}" method="POST" title="Restore Record">
                                @csrf @method('PATCH')
                                <button type="submit" class="p-1.5 text-slate-400 hover:text-indigo-600 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                </button>
                            </form>
                            {{-- Permanent Delete --}}
                            <form action="{{ route('admin.archive.force-delete', [$type, $record->id]) }}" method="POST"
                                  onsubmit="return confirm('Permanently delete this record? This cannot be undone.')" title="Delete Forever">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-rose-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-16 text-center">
                        <div class="w-12 h-12 bg-gray-50 rounded-xl border border-gray-100 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </div>
                        <p class="text-gray-900 font-medium mb-1">No archived {{ str_replace('-', ' ', $type) }}</p>
                        <p class="text-gray-400 text-sm">When you delete {{ str_replace('-', ' ', $type) }}, they will appear here.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($records->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $records->withQueryString()->links() }}
    </div>
    @endif
</div>
