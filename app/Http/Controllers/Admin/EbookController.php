<?php

namespace App\Http\Controllers\Admin;

use App\Services\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Category;
use App\Models\Ebook;
use App\Traits\HandlesAdminFilters;
use Illuminate\Http\Request;

class EbookController extends Controller
{
    use HandlesAdminFilters;

    public function index(Request $request)
    {
        $query = Ebook::with('authors', 'category');

        $query = $this->applyFilters(
            $query,
            $request,
            'filter_ebooks',
            ['title', 'authors.first_name', 'authors.last_name'], // searchableFields
            ['access_level', 'category_id'] // filterableFields
        );

        $ebooks = $query->paginate(10)->appends($request->query());
        $categories = Category::orderBy('name')->get();

        return view('admin.ebooks.index', compact('ebooks', 'categories'));
    }

    public function create()
    {
        $authors    = Author::orderBy('last_name')->get();
        $categories = Category::orderBy('name')->get();
        return view('admin.ebooks.create', compact('authors', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'author_ids'   => 'required|array|min:1',
            'author_ids.*' => 'exists:authors,id',
            'category_id'  => 'required|exists:categories,id',
            'isbn'         => 'nullable|string|unique:ebooks,isbn',
            'publisher'    => 'nullable|string|max:255',
            'publish_year' => 'nullable|integer|min:1000|max:9999',
            'file_path'    => 'required|file|mimes:pdf,epub,mp3',
            'cover_image'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'file_type'    => 'required|in:pdf,epub,mp3',
            'access_level' => 'required|in:free,basic,premium',
            'is_featured'  => 'nullable|boolean',
            'tags'         => 'nullable|string',
            'preview_pages'=> 'nullable|integer|min:0|max:50',
        ]);

        $validated['file_path'] = $request->file('file_path')->store('ebooks', 'private');

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        $ebook = Ebook::create([
            'category_id'  => $validated['category_id'],
            'title'        => $validated['title'],
            'isbn'         => $validated['isbn'] ?? null,
            'publisher'    => $validated['publisher'] ?? null,
            'publish_year' => $validated['publish_year'] ?? null,
            'file_path'    => $validated['file_path'],
            'cover_image'  => $validated['cover_image'] ?? null,
            'file_type'    => $validated['file_type'],
            'access_level' => $validated['access_level'],
            'is_featured'  => $request->has('is_featured'),
            'preview_pages'=> (int) ($validated['preview_pages'] ?? 10),
        ]);

        $ebook->authors()->sync($request->author_ids);

        // Handle Tags
        if ($request->filled('tags')) {
            $tagNames = array_unique(array_filter(array_map('trim', explode(',', $request->tags))));
            foreach ($tagNames as $tag) {
                $ebook->tags()->create(['tag_name' => $tag]);
            }
        }

        ActivityLogger::log('created', 'ebooks', 'Created new ebook: ' . $ebook->title);
        return redirect()->route('admin.ebooks.index')->with('success', 'Ebook created successfully.');
    }

    public function show($id)
    {
        $ebook = Ebook::with('authors', 'category', 'reviews.member', 'tags')->findOrFail($id);

        $totalAccesses   = $ebook->accesses()->count();
        $recentAccessors = $ebook->accesses()
            ->with('member')
            ->latest()
            ->limit(10)
            ->get();
        $avgRating = $ebook->reviews()->where('status', 'approved')->avg('rating');

        // Fetch related ebooks
        $authorIds = $ebook->authors->pluck('id');
        
        $sameCatSameAuthor = Ebook::with('authors', 'category')->where('id', '!=', $ebook->id)
            ->where('category_id', $ebook->category_id)
            ->whereHas('authors', fn($q) => $q->whereIn('authors.id', $authorIds))
            ->latest()->get();

        $sameAuthor = Ebook::with('authors', 'category')->where('id', '!=', $ebook->id)
            ->whereHas('authors', fn($q) => $q->whereIn('authors.id', $authorIds))
            ->whereNotIn('id', $sameCatSameAuthor->pluck('id'))
            ->latest()->get();
            
        $sameCat = Ebook::with('authors', 'category')->where('id', '!=', $ebook->id)
            ->where('category_id', $ebook->category_id)
            ->whereNotIn('id', $sameCatSameAuthor->pluck('id')->merge($sameAuthor->pluck('id')))
            ->latest()->get();

        $relatedEbooks = $sameCatSameAuthor->concat($sameAuthor)->concat($sameCat)->take(4);

        return view('admin.ebooks.show', compact(
            'ebook',
            'totalAccesses',
            'recentAccessors',
            'avgRating',
            'relatedEbooks'
        ));
    }

    public function edit($id)
    {
        $ebook      = Ebook::with('authors', 'tags')->findOrFail($id);
        $authors    = Author::orderBy('last_name')->get();
        $categories = Category::orderBy('name')->get();
        return view('admin.ebooks.edit', compact('ebook', 'authors', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $ebook = Ebook::findOrFail($id);

        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'author_ids'   => 'required|array|min:1',
            'author_ids.*' => 'exists:authors,id',
            'category_id'  => 'required|exists:categories,id',
            'isbn'         => 'nullable|string|unique:ebooks,isbn,' . $id,
            'publisher'    => 'nullable|string|max:255',
            'publish_year' => 'nullable|integer|min:1000|max:9999',
            'file_path'    => 'nullable|file|mimes:pdf,epub,mp3',
            'cover_image'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'file_type'    => 'required|in:pdf,epub,mp3',
            'access_level' => 'required|in:free,basic,premium',
            'is_featured'  => 'nullable|boolean',
            'tags'         => 'nullable|string',
            'preview_pages'=> 'nullable|integer|min:0|max:50',
        ]);

        if ($request->hasFile('file_path')) {
            $validated['file_path'] = $request->file('file_path')->store('ebooks', 'private');
        } else {
            unset($validated['file_path']);
        }

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        } else {
            unset($validated['cover_image']);
        }

        $ebook->update([
            'category_id'  => $validated['category_id'],
            'title'        => $validated['title'],
            'isbn'         => $validated['isbn'] ?? $ebook->isbn,
            'publisher'    => $validated['publisher'] ?? $ebook->publisher,
            'publish_year' => $validated['publish_year'] ?? $ebook->publish_year,
            'file_path'    => $validated['file_path'] ?? $ebook->file_path,
            'cover_image'  => $validated['cover_image'] ?? $ebook->cover_image,
            'file_type'    => $validated['file_type'],
            'access_level' => $validated['access_level'],
            'is_featured'  => $request->has('is_featured'),
            'preview_pages'=> (int) ($validated['preview_pages'] ?? $ebook->preview_pages),
        ]);

        $ebook->authors()->sync($request->author_ids);

        // Update Tags
        $ebook->tags()->delete();
        if ($request->filled('tags')) {
            $tagNames = array_unique(array_filter(array_map('trim', explode(',', $request->tags))));
            foreach ($tagNames as $tag) {
                $ebook->tags()->create(['tag_name' => $tag]);
            }
        }

        ActivityLogger::log('updated', 'ebooks', 'Updated ebook: ' . $ebook->title);
        return redirect()->route('admin.ebooks.index')->with('success', 'Ebook updated successfully.');
    }

    public function destroy($id)
    {
        $ebook = Ebook::findOrFail($id);
        ActivityLogger::log('deleted', 'ebooks', 'Deleted ebook: ' . $ebook->title);
        $ebook->delete();
        return redirect()->route('admin.ebooks.index')->with('success', 'Ebook deleted successfully.');
    }

    public function stream(Ebook $ebook)
    {
        $filePath = null;

        if (\Illuminate\Support\Facades\Storage::disk('private')->exists($ebook->file_path)) {
            $filePath = \Illuminate\Support\Facades\Storage::disk('private')->path($ebook->file_path);
        } elseif (\Illuminate\Support\Facades\Storage::disk('public')->exists($ebook->file_path)) {
            $filePath = \Illuminate\Support\Facades\Storage::disk('public')->path($ebook->file_path);
        } else {
            $absolute = storage_path('app/' . $ebook->file_path);
            if (file_exists($absolute)) {
                $filePath = $absolute;
            }
        }

        if (! $filePath) {
            abort(404, 'File not found on server.');
        }

        $headers = [];
        if ($ebook->file_type === 'pdf') {
            $headers = ['Content-Type' => 'application/pdf'];
        }

        return response()->file($filePath, $headers);
    }

    public function spotlight(Ebook $ebook)
    {
        $isTurningOn = !$ebook->is_spotlighted;

        if ($isTurningOn) {
            // Un-spotlight all other ebooks
            Ebook::query()->update(['is_spotlighted' => false]);
            $ebook->update(['is_spotlighted' => true]);
            ActivityLogger::log('updated', 'ebooks', "Set spotlight to ebook: {$ebook->title}");
            $message = 'Ebook set as spotlight.';
        } else {
            $ebook->update(['is_spotlighted' => false]);
            ActivityLogger::log('updated', 'ebooks', "Removed spotlight from ebook: {$ebook->title}");
            $message = 'Ebook spotlight removed.';
        }

        return back()->with('success', $message);
    }
}
