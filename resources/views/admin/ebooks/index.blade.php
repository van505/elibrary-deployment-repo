@extends('layouts.admin')
@section('title', 'Ebooks')

@section('content')
<div class="space-y-5">
    <div class="flex items-center justify-between mb-2">
        <h1 class="text-xl font-bold text-gray-800">Ebooks Management</h1>
    </div>

    <x-admin.filter-bar 
        :action="route('admin.ebooks.index')" 
        searchPlaceholder="Search by title or author..."
        :sortable="['created_at' => 'Date Added', 'title' => 'Title', 'publish_year' => 'Publish Year']"
        :createRoute="route('admin.ebooks.create')"
        createLabel="Add Ebook">
        
        <select name="category_id" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>{{ $cat->name }}</option>
            @endforeach
        </select>
        
        <select name="access_level" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            <option value="">All Access Levels</option>
            <option value="free" @selected(request('access_level') === 'free')>Free</option>
            <option value="basic" @selected(request('access_level') === 'basic')>Basic</option>
            <option value="premium" @selected(request('access_level') === 'premium')>Premium</option>
        </select>
        
    </x-admin.filter-bar>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left font-semibold text-gray-600 px-6 py-3">Cover</th>
                    <th class="text-left font-semibold text-gray-600 px-6 py-3">Title</th>
                    <th class="text-left font-semibold text-gray-600 px-6 py-3">Authors</th>
                    <th class="text-left font-semibold text-gray-600 px-6 py-3">Category</th>
                    <th class="text-left font-semibold text-gray-600 px-6 py-3">Format</th>
                    <th class="text-left font-semibold text-gray-600 px-6 py-3">Access</th>
                    <th class="text-left font-semibold text-gray-600 px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($ebooks as $ebook)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3">
                        @if($ebook->cover_image)
                            <img src="{{ asset('storage/' . $ebook->cover_image) }}" class="w-10 h-14 object-cover rounded shadow-sm">
                        @else
                            <div class="w-10 h-14 bg-blue-100 rounded flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13"/></svg>
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-3 font-medium text-gray-800 max-w-xs">{{ $ebook->title }}</td>
                    <td class="px-6 py-3 text-gray-500 text-xs">{{ $ebook->authors->pluck('full_name')->join(', ') ?: '—' }}</td>
                    <td class="px-6 py-3">
                        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-0.5 rounded-full">{{ $ebook->category->name ?? '—' }}</span>
                    </td>
                    <td class="px-6 py-3">
                        <span class="bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded-full uppercase">{{ $ebook->file_type }}</span>
                    </td>
                    <td class="px-6 py-3">
                        @php $levelColors = ['free'=>'bg-green-100 text-green-700','basic'=>'bg-blue-100 text-blue-700','premium'=>'bg-purple-100 text-purple-700']; @endphp
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $levelColors[$ebook->access_level] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ ucfirst($ebook->access_level) }}
                        </span>
                    </td>
                    <td class="px-6 py-3">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.ebooks.show', $ebook) }}" class="text-blue-600 hover:underline text-xs">View</a>
                            <a href="{{ route('admin.ebooks.edit', $ebook) }}" class="text-yellow-600 hover:underline text-xs">Edit</a>
                            <form action="{{ route('admin.ebooks.destroy', $ebook) }}" method="POST" onsubmit="return confirm('Delete this ebook?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline text-xs">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-6 py-10 text-center text-gray-400">No ebooks found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100">{{ $ebooks->links() }}</div>
    </div>
</div>
@endsection
