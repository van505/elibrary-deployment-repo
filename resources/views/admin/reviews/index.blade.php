@extends('layouts.admin')
@section('title', 'Reviews')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Reviews</h2>
    <div class="flex gap-2">
        @foreach(['all','pending','approved','rejected'] as $s)
        <a href="{{ route('admin.reviews.index', ['status' => $s]) }}"
           class="px-3 py-1.5 rounded text-sm {{ $status === $s ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-100 shadow-sm' }}">
            {{ ucfirst($s) }}
        </a>
        @endforeach
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
            <tr>
                <th class="px-6 py-3">Member</th>
                <th class="px-6 py-3">Ebook</th>
                <th class="px-6 py-3">Rating</th>
                <th class="px-6 py-3">Comment</th>
                <th class="px-6 py-3">Status</th>
                <th class="px-6 py-3">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reviews as $r)
            <tr class="border-t border-gray-100 hover:bg-gray-50">
                <td class="px-6 py-3">{{ $r->member->user->name }}</td>
                <td class="px-6 py-3 max-w-xs truncate">{{ $r->ebook->title }}</td>
                <td class="px-6 py-3">
                    <span class="text-yellow-500">{{ str_repeat('★', $r->rating) }}</span><span class="text-gray-300">{{ str_repeat('★', 5 - $r->rating) }}</span>
                </td>
                <td class="px-6 py-3 text-gray-500 max-w-xs truncate">{{ $r->comment ?? '—' }}</td>
                <td class="px-6 py-3">
                    @php $sc = ['pending'=>'bg-yellow-100 text-yellow-700','approved'=>'bg-green-100 text-green-700','rejected'=>'bg-red-100 text-red-700']; @endphp
                    <span class="px-2 py-0.5 rounded text-xs font-medium {{ $sc[$r->status] ?? '' }}">{{ $r->status }}</span>
                </td>
                <td class="px-6 py-3 flex gap-2">
                    @if($r->status === 'pending')
                    <form action="{{ route('admin.reviews.update', $r) }}" method="POST">
                        @csrf @method('PUT')
                        <input type="hidden" name="status" value="approved">
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs">Approve</button>
                    </form>
                    <form action="{{ route('admin.reviews.update', $r) }}" method="POST">
                        @csrf @method('PUT')
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">Reject</button>
                    </form>
                    @endif
                    <form action="{{ route('admin.reviews.destroy', $r) }}" method="POST" onsubmit="return confirm('Delete review?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="bg-gray-400 hover:bg-gray-500 text-white px-3 py-1 rounded text-xs">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-6 py-8 text-center text-gray-400">No reviews found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100">{{ $reviews->links() }}</div>
</div>
@endsection
