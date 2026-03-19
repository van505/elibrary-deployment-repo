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
        $ebooks = Ebook::with('author', 'category')->paginate(10);

        return view('admin.ebooks.index', compact('ebooks'));
    }

    public function create()
    {
        $authors    = Author::all();
        $categories = Category::all();

        return view('admin.ebooks.create', compact('authors', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'author_id'    => 'required|exists:authors,id',
            'category_id'  => 'required|exists:categories,id',
            'isbn'         => 'nullable|string|unique:ebooks,isbn',
            'publisher'    => 'nullable|string|max:255',
            'publish_year' => 'nullable|integer|min:1000|max:9999',
            'file_path'    => 'required|file|mimes:pdf,epub',
            'cover_image'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'total_copies' => 'required|integer|min:1',
        ]);

        $validated['file_path'] = $request->file('file_path')
            ->store('ebooks', 'public');

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')
                ->store('covers', 'public');
        }

        $validated['available_copies'] = $validated['total_copies'];

        $ebook = Ebook::create($validated);

        ActivityLogger::log('created', 'ebooks', 'Created new ebook: ' . $ebook->title);

        return redirect()->route('admin.ebooks.index')->with('success', 'Ebook created successfully.');
    }

    public function show($id)
    {
        $ebook = Ebook::with('author', 'category', 'reviews')->findOrFail($id);

        return view('admin.ebooks.show', compact('ebook'));
    }

    public function edit($id)
    {
        $ebook      = Ebook::findOrFail($id);
        $authors    = Author::all();
        $categories = Category::all();

        return view('admin.ebooks.edit', compact('ebook', 'authors', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $ebook = Ebook::findOrFail($id);

        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'author_id'    => 'required|exists:authors,id',
            'category_id'  => 'required|exists:categories,id',
            'isbn'         => 'nullable|string|unique:ebooks,isbn,' . $id,
            'publisher'    => 'nullable|string|max:255',
            'publish_year' => 'nullable|integer|min:1000|max:9999',
            'file_path'    => 'nullable|file|mimes:pdf,epub',
            'cover_image'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'total_copies' => 'required|integer|min:1',
        ]);

        if ($request->hasFile('file_path')) {
            $validated['file_path'] = $request->file('file_path')
                ->store('ebooks', 'public');
        } else {
            unset($validated['file_path']);
        }

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')
                ->store('covers', 'public');
        } else {
            unset($validated['cover_image']);
        }

        $ebook->update($validated);

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
