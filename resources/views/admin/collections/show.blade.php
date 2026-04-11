@extends('layouts.admin')

@section('header')
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.collections.index') }}" class="text-gray-400 hover:text-gray-600 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Manage Books: {{ $collection->name }}</h2>
            <p class="text-sm text-gray-500">Arrange the books in the correct series order.</p>
        </div>
    </div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    {{-- Left Column: Books List --}}
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <h3 class="font-bold text-gray-900">Books in Series</h3>
                <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-0.5 rounded-full">{{ $ebooks->count() }} Books</span>
            </div>
            
            <div class="divide-y divide-gray-100">
                @forelse($ebooks as $index => $ebook)
                    <div class="p-4 flex items-center justify-between hover:bg-gray-50 transition">
                        
                        <div class="flex items-center gap-4">
                            {{-- Order Number / Badge --}}
                            <div class="flex flex-col items-center justify-center w-10 text-center">
                                <span class="text-sm font-bold text-gray-500">#{{ $ebook->pivot->order_number }}</span>
                            </div>
                            
                            {{-- Cover --}}
                            @if($ebook->cover_image)
                                <img src="{{ Storage::url($ebook->cover_image) }}" class="w-12 h-16 object-cover rounded shadow-sm">
                            @else
                                <div class="w-12 h-16 bg-gray-200 rounded flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                </div>
                            @endif
                            
                            {{-- Info --}}
                            <div>
                                <div class="font-bold text-gray-900">{{ $ebook->title }}</div>
                                <div class="text-xs text-gray-500">{{ $ebook->authors->pluck('name')->join(', ') }}</div>
                            </div>
                        </div>

                        {{-- Actions: Up, Down, Remove --}}
                        <div class="flex items-center gap-2">
                            <div class="flex flex-col gap-1 border-r border-gray-200 pr-3 mr-1">
                                <form action="{{ route('admin.collections.move-ebook', [$collection->id, $ebook->id, 'up']) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" @disabled($loop->first) class="{{ $loop->first ? 'text-gray-300' : 'text-gray-500 hover:text-blue-600' }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                                    </button>
                                </form>
                                <form action="{{ route('admin.collections.move-ebook', [$collection->id, $ebook->id, 'down']) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" @disabled($loop->last) class="{{ $loop->last ? 'text-gray-300' : 'text-gray-500 hover:text-blue-600' }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                </form>
                            </div>
                            
                            <form action="{{ route('admin.collections.remove-ebook', [$collection->id, $ebook->id]) }}" method="POST" onsubmit="return confirm('Remove this book from the series?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-gray-400 hover:text-red-600 transition rounded-full hover:bg-red-50" title="Remove from Collection">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </form>
                        </div>
                        
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-500">
                        This collection is empty. Add books using the form on the right.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Right Column: Collection Summary & Add Book --}}
    <div class="space-y-6">
        
        {{-- Add Book Form --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Add Next Book
            </h3>
            
            <form action="{{ route('admin.collections.add-ebook', $collection->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Select Ebook</label>
                    <select name="ebook_id" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Select Ebook --</option>
                        @foreach($availableEbooks as $eb)
                            <option value="{{ $eb->id }}">{{ $eb->title }} ({{ $eb->access_level }})</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="w-full px-4 py-2 bg-gray-900 text-white rounded-lg text-sm font-semibold hover:bg-gray-800 transition">
                    Add to Series
                </button>
            </form>
            @if($availableEbooks->isEmpty())
                <p class="mt-3 text-xs text-orange-600">All available ebooks are already in this collection.</p>
            @endif
        </div>

        {{-- Collection Summary --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            @if($collection->cover_image)
                <div class="h-32 w-full bg-cover bg-center" style="background-image: url('{{ Storage::url($collection->cover_image) }}'); opacity: 0.8;"></div>
            @else
                <div class="h-24 w-full bg-gradient-to-r from-blue-500 to-indigo-600"></div>
            @endif
            
            <div class="p-6 relative">
                <h3 class="font-bold text-lg text-gray-900">{{ $collection->name }}</h3>
                <p class="text-sm text-gray-500 mt-2 mb-4">{{ $collection->description }}</p>
                
                <div class="flex items-center justify-between text-sm pt-4 border-t border-gray-100">
                    <span class="text-gray-500">Status:</span>
                    @if($collection->is_active)
                        <span class="font-semibold text-green-600">Active</span>
                    @else
                        <span class="font-semibold text-red-600">Inactive</span>
                    @endif
                </div>
                
                <div class="mt-6 pt-4 border-t border-gray-100 text-center">
                     <a href="{{ route('admin.collections.edit', $collection->id) }}" class="text-sm text-blue-600 hover:underline">Edit Collection Details</a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
