@extends('layouts.admin')
@section('title', 'Ebooks')

@section('content')
<div class="space-y-5">

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
                            <form action="{{ route('admin.ebooks.spotlight', $ebook) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-xs flex items-center gap-1 {{ $ebook->is_spotlighted ? 'text-amber-500 font-semibold' : 'text-gray-400 hover:text-amber-500' }}" title="{{ $ebook->is_spotlighted ? 'Spotlighted' : 'Set as Spotlight' }}">
                                    @if($ebook->is_spotlighted)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg> Spotlighted
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                        </svg> Set Spotlight
                                    @endif
                                </button>
                            </form>
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
