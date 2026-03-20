<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Category;
use App\Models\Ebook;
use Illuminate\Http\Request;

class EbookController extends Controller
{
    public function index()
    {
        $ebooks = Ebook::with('authors', 'category')->paginate(10);
        return view('admin.ebooks.index', compact('ebooks'));
    }

    public function create()
    {
        $authors    = Author::orderBy('name')->get();
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
        ]);

        $validated['file_path'] = $request->file('file_path')->store('ebooks', 'public');

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
        ]);

        $ebook->authors()->sync($request->author_ids);

        ActivityLogger::log('created', 'ebooks', 'Created new ebook: ' . $ebook->title);
        return redirect()->route('admin.ebooks.index')->with('success', 'Ebook created successfully.');
    }

    public function show($id)
    {
        $ebook = Ebook::with('authors', 'category', 'reviews')->findOrFail($id);
        return view('admin.ebooks.show', compact('ebook'));
    }

    public function edit($id)
    {
        $ebook      = Ebook::with('authors')->findOrFail($id);
        $authors    = Author::orderBy('name')->get();
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
        ]);

        if ($request->hasFile('file_path')) {
            $validated['file_path'] = $request->file('file_path')->store('ebooks', 'public');
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
        ]);

        $ebook->authors()->sync($request->author_ids);

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
}
