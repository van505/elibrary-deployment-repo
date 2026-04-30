<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Ebook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\ActivityLogger;

class CollectionController extends Controller
{
    public function index(Request $request)
    {
        $query = Collection::query()->withCount('ebooks');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Sorting
        $sort = $request->get('sort', 'latest');
        if ($sort === 'oldest') {
            $query->oldest();
        } elseif ($sort === 'name_asc') {
            $query->orderBy('name', 'asc');
        } elseif ($sort === 'name_desc') {
            $query->orderBy('name', 'desc');
        } else {
            $query->latest();
        }

        $collections = $query->paginate(20)->appends($request->query());

        // Stats
        $totalCollections  = \App\Models\Collection::count();
        $activeCollections = \App\Models\Collection::where('is_active', true)->count();
        $totalEbooksInCollections = \App\Models\Collection::withCount('ebooks')->get()->sum('ebooks_count');
        $avgEbooksPerCollection = $totalCollections > 0 ? round($totalEbooksInCollections / $totalCollections, 1) : 0;

        return view('admin.collections.index', compact('collections', 'totalCollections', 'activeCollections', 'totalEbooksInCollections', 'avgEbooksPerCollection'));
    }

    public function create()
    {
        return view('admin.collections.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:collections,name',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('collections', 'public');
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['created_by'] = auth()->id();

        $collection = Collection::create($validated);
        
        ActivityLogger::log('created', 'collections', "Created collection: {$collection->name}");

        return redirect()->route('admin.collections.index')->with('success', 'Collection created successfully.');
    }

    public function show(Collection $collection)
    {
        $ebooks = $collection->ebooks()->get();
        // Exclude ebooks already in the collection for the dropdown
        $availableEbooks = Ebook::whereNotIn('id', $ebooks->pluck('id'))
                                ->orderBy('title')
                                ->get();

        return view('admin.collections.show', compact('collection', 'ebooks', 'availableEbooks'));
    }

    public function edit(Collection $collection)
    {
        return view('admin.collections.edit', compact('collection'));
    }

    public function update(Request $request, Collection $collection)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:collections,name,' . $collection->id,
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('cover_image')) {
            if ($collection->cover_image) {
                Storage::disk('public')->delete($collection->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('collections', 'public');
        }

        $validated['is_active'] = $request->has('is_active');

        $collection->update($validated);
        
        ActivityLogger::log('updated', 'collections', "Updated collection: {$collection->name}");

        return redirect()->route('admin.collections.index')->with('success', 'Collection updated successfully.');
    }

    public function destroy(Collection $collection)
    {
        if ($collection->cover_image) {
            Storage::disk('public')->delete($collection->cover_image);
        }
        
        ActivityLogger::log('deleted', 'collections', "Deleted collection: {$collection->name}");
        $collection->delete();

        return redirect()->route('admin.collections.index')->with('success', 'Collection deleted successfully.');
    }

    public function addEbook(Request $request, Collection $collection)
    {
        $request->validate([
            'ebook_id' => 'required|exists:ebooks,id',
        ]);

        $maxOrder = $collection->collectionEbooks()->max('order_number') ?? 0;

        $collection->collectionEbooks()->create([
            'ebook_id' => $request->ebook_id,
            'order_number' => $maxOrder + 1,
        ]);

        ActivityLogger::log('updated', 'collections', "Added ebook to collection: {$collection->name}");

        return redirect()->route('admin.collections.show', $collection->id)
                         ->with('success', 'Ebook added to collection.');
    }

    public function removeEbook(Collection $collection, Ebook $ebook)
    {
        $collection->collectionEbooks()->where('ebook_id', $ebook->id)->delete();
        
        ActivityLogger::log('updated', 'collections', "Removed ebook from collection: {$collection->name}");

        // Reorder remaining to close gaps
        $this->resequenceOrder($collection);

        return redirect()->route('admin.collections.show', $collection->id)
                         ->with('success', 'Ebook removed from collection.');
    }

    public function moveEbook(Request $request, Collection $collection, Ebook $ebook, $direction)
    {
        $currentPivot = $collection->collectionEbooks()->where('ebook_id', $ebook->id)->firstOrFail();
        $currentOrder = $currentPivot->order_number;

        if ($direction === 'up' && $currentOrder > 1) {
            $swapPivot = $collection->collectionEbooks()->where('order_number', '<', $currentOrder)
                                      ->orderBy('order_number', 'desc')->first();
        } elseif ($direction === 'down') {
            $swapPivot = $collection->collectionEbooks()->where('order_number', '>', $currentOrder)
                                      ->orderBy('order_number', 'asc')->first();
        }

        if (isset($swapPivot)) {
            $swapOrder = $swapPivot->order_number;
            $swapPivot->update(['order_number' => $currentOrder]);
            $currentPivot->update(['order_number' => $swapOrder]);
        }

        return redirect()->route('admin.collections.show', $collection->id);
    }
    
    private function resequenceOrder(Collection $collection)
    {
        $pivots = $collection->collectionEbooks()->orderBy('order_number')->get();
        $i = 1;
        foreach ($pivots as $pivot) {
            $pivot->update(['order_number' => $i]);
            $i++;
        }
    }
}
