@extends('layouts.admin')
@section('title', 'Archive')

@section('content')
<div class="space-y-5">

    <div>
        <h2 class="text-2xl font-bold text-gray-800">Archive</h2>
        <p class="text-sm text-gray-500 mt-1">Soft-deleted records. You can restore or permanently delete them here.</p>
    </div>

    {{-- Tab Navigation --}}
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
        <a href="{{ route('admin.archive.index', ['type' => $key]) }}"
           class="px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-1.5 transition-colors
                  {{ $type === $key ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-100 shadow-sm' }}">
            {{ $label }}
            @if(($counts[$key] ?? 0) > 0)
                <span class="inline-flex items-center justify-center min-w-[1.25rem] h-5 px-1 text-xs font-bold rounded-full
                             {{ $type === $key ? 'bg-white text-blue-700' : 'bg-red-500 text-white' }}">
                    {{ $counts[$key] }}
                </span>
            @endif
        </a>
        @endforeach
    </div>

    {{-- Records Table --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3">Record</th>
                        <th class="px-6 py-3">Details</th>
                        <th class="px-6 py-3">Archived On</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $record)
                    <tr class="border-t border-gray-100 hover:bg-gray-50">
                        <td class="px-6 py-3 font-medium text-gray-800">
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
                        <td class="px-6 py-3 text-gray-400 text-xs">
                            @switch($type)
                                @case('members')
                                    Code: {{ $record->member_code }} | Status: {{ $record->status }}
                                    @break
                                @case('ebooks')
                                    ISBN: {{ $record->isbn ?? '—' }} | {{ ucfirst($record->access_level) }}
                                    @break
                                @case('authors')
                                    {{ $record->nationality ?? '—' }}
                                    @break
                                @case('categories')
                                    {{ $record->description ?? '—' }}
                                    @break
                                @case('reviews')
                                    Status: {{ ucfirst($record->status) }}
                                    @break
                                @case('subscription-plans')
                                    ₱{{ number_format($record->price, 2) }} / plan
                                    @break
                            @endswitch
                        </td>
                        <td class="px-6 py-3 text-gray-400 whitespace-nowrap">
                            {{ $record->deleted_at->format('M d, Y h:i A') }}
                        </td>
                        <td class="px-6 py-3 text-right">
                            <div class="flex justify-end gap-2">
                                {{-- Restore --}}
                                <form action="{{ route('admin.archive.restore', [$type, $record->id]) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                            class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded text-xs font-medium transition-colors">
                                        ↩ Restore
                                    </button>
                                </form>
                                {{-- Permanent Delete --}}
                                <form action="{{ route('admin.archive.force-delete', [$type, $record->id]) }}" method="POST"
                                      onsubmit="return confirm('Permanently delete this record? This cannot be undone.')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded text-xs font-medium transition-colors">
                                        🗑 Delete Forever
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            <p class="font-medium">No archived {{ str_replace('-', ' ', $type) }}.</p>
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

</div>
@endsection
