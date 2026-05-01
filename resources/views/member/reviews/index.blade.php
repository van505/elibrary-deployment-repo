@extends('layouts.member')
@section('title', 'My Reviews')

@push('breadcrumbs')
<nav class="flex items-center text-sm">
    <a href="{{ route('member.dashboard') }}" class="text-gray-400 hover:text-gray-600 transition-colors duration-150">Home</a>
    <span class="text-gray-300 mx-1.5 select-none">›</span>
    <span class="text-gray-400 mx-1.5">My Account</span>
    <span class="text-gray-300 mx-1.5 select-none">›</span>
    <span class="text-gray-700 font-medium">My Reviews</span>
</nav>
@endpush

@section('content')
<div class="space-y-6" x-data="{ 
    showEditModal: false, 
    editForm: { id: null, rating: 5, comment: '', url: '' }, 
    openEditModal(review) { 
        this.editForm.id = review.id; 
        this.editForm.rating = review.rating; 
        this.editForm.comment = review.comment || ''; 
        this.editForm.url = '{{ url('member/reviews') }}/' + review.id; 
        this.showEditModal = true; 
    } 
}">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">My Reviews</h2>
        <p class="text-sm text-gray-500 mt-1">All reviews you've submitted</p>
    </div>

    @if($reviews->isEmpty())
        <div class="bg-slate-50 border-2 border-dashed border-slate-200 rounded-xl p-16 text-center text-gray-400 flex flex-col items-center justify-center">
            <svg class="w-16 h-16 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
            </svg>
            <p class="font-medium text-lg text-gray-700">No Reviews</p>
            <p class="text-sm mt-1 mb-4">You haven't submitted any reviews yet.</p>
            <a href="{{ route('member.ebooks.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-6 rounded-lg transition-colors">
                Browse Ebooks
            </a>
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mt-6">
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3">Ebook</th>
                        <th class="px-6 py-3">Rating</th>
                        <th class="px-6 py-3">Comment</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Date</th>
                        <th class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reviews as $review)
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors last:border-0">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                @if($review->ebook->cover_image)
                                    <img src="{{ Storage::url($review->ebook->cover_image) }}" alt="" class="w-10 h-14 object-cover rounded shadow-sm border border-gray-100 flex-shrink-0">
                                @else
                                    <div class="w-10 h-14 bg-gradient-to-br from-indigo-100 to-purple-100 rounded flex items-center justify-center border border-gray-100 flex-shrink-0">
                                        <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    </div>
                                @endif
                                <div>
                                    <a href="{{ route('member.ebooks.show', $review->ebook->id) }}" class="text-sm font-medium text-gray-900 hover:text-indigo-600 transition-colors line-clamp-2">
                                        {{ $review->ebook->title }}
                                    </a>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $review->ebook->authors->pluck('name')->join(', ') ?: 'Unknown Author' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-0.5">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="{{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-200' }} text-base">★</span>
                                @endfor
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="max-w-xs text-sm text-gray-600 line-clamp-2" title="{{ $review->comment }}">
                                {{ $review->comment ?: '—' }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($review->status === 'pending')
                                <span class="bg-yellow-50 text-yellow-700 text-xs font-semibold px-2.5 py-1 rounded-full border border-yellow-200">⏳ Awaiting Approval</span>
                            @elseif($review->status === 'approved')
                                <span class="bg-emerald-50 text-emerald-700 text-xs font-semibold px-2.5 py-1 rounded-full border border-emerald-200">✓ Published</span>
                            @else
                                <span class="bg-red-50 text-red-700 text-xs font-semibold px-2.5 py-1 rounded-full border border-red-200">✕ Rejected</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-400 whitespace-nowrap">{{ $review->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                {{-- Edit Action --}}
                                <button type="button" @click.prevent="openEditModal({{ json_encode(['id' => $review->id, 'rating' => $review->rating, 'comment' => $review->comment]) }})" class="text-gray-400 hover:text-indigo-600 transition-colors duration-200 cursor-pointer" title="Edit Review">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                
                                {{-- Delete Action --}}
                                <form action="{{ route('member.reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Delete this review?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors duration-200 cursor-pointer" title="Delete Review">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="bg-gray-50 px-6 py-4 border-t border-slate-200">{{ $reviews->links() }}</div>
        </div>
    @endif

    {{-- Edit Review Modal --}}
    <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-[9999] w-full h-full bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
         
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden" @click.away="showEditModal = false"
             x-show="showEditModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
             
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900">Edit Review</h3>
                <button type="button" @click="showEditModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <form :action="editForm.url" method="POST">
                @csrf
                @method('PUT')
                
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Rating</label>
                        <div class="flex items-center gap-2">
                            <template x-for="i in 5">
                                <button type="button" @click="editForm.rating = i" class="text-2xl focus:outline-none transition-colors"
                                        :class="i <= editForm.rating ? 'text-yellow-400' : 'text-gray-200 hover:text-yellow-200'">
                                    ★
                                </button>
                            </template>
                        </div>
                        <input type="hidden" name="rating" x-model="editForm.rating">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Comment</label>
                        <textarea name="comment" x-model="editForm.comment" rows="4" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm p-3 border" placeholder="Write your thoughts..."></textarea>
                    </div>
                </div>
                
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
                    <button type="button" @click="showEditModal = false" class="text-gray-600 hover:text-gray-800 font-medium px-4 py-2 text-sm transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-6 rounded-lg transition-colors">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
