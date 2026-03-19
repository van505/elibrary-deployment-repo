@extends('layouts.member')
@section('title', 'My Reservations')

@section('content')
<h2 class="text-2xl font-bold text-gray-800 mb-6">My Reservations</h2>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
            <tr>
                <th class="px-6 py-3">Ebook</th>
                <th class="px-6 py-3">Reserved Date</th>
                <th class="px-6 py-3">Expiry Date</th>
                <th class="px-6 py-3">Status</th>
                <th class="px-6 py-3">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reservations as $r)
            <tr class="border-t border-gray-100 hover:bg-gray-50">
                <td class="px-6 py-3 font-medium text-gray-800">{{ $r->ebook->title }}</td>
                <td class="px-6 py-3 text-gray-500">{{ $r->reserved_date?->format('M d, Y') }}</td>
                <td class="px-6 py-3 text-gray-500">{{ $r->expiry_date?->format('M d, Y') ?? '—' }}</td>
                <td class="px-6 py-3">
                    @php $sc = ['pending'=>'bg-yellow-100 text-yellow-700','ready'=>'bg-green-100 text-green-700','cancelled'=>'bg-gray-100 text-gray-500','expired'=>'bg-red-100 text-red-700']; @endphp
                    <span class="px-2 py-0.5 rounded text-xs font-medium {{ $sc[$r->status] ?? '' }}">{{ $r->status }}</span>
                </td>
                <td class="px-6 py-3">
                    @if($r->status === 'pending')
                    <form action="{{ route('member.reservations.destroy', $r) }}" method="POST" onsubmit="return confirm('Cancel this reservation?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">Cancel</button>
                    </form>
                    @else
                    <span class="text-gray-300 text-xs">—</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400">No reservations yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100">{{ $reservations->links() }}</div>
</div>
@endsection
