<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index()
    {
        $authors = Author::paginate(10);

        return view('admin.authors.index', compact('authors'));
    }

    public function create()
    {
        return view('admin.authors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'bio'         => 'nullable|string',
            'nationality' => 'nullable|string|max:255',
        ]);

        $author = Author::create($validated);

        ActivityLogger::log('created', 'authors', 'Created new author: ' . $author->name);

        return redirect()->route('admin.authors.index')->with('success', 'Author created successfully.');
    }

    public function show($id)
    {
        $author = Author::with('ebooks')->findOrFail($id);

        return view('admin.authors.show', compact('author'));
    }

    public function edit($id)
    {
        $author = Author::findOrFail($id);

        return view('admin.authors.edit', compact('author'));
    }

    public function update(Request $request, $id)
    {
        $author = Author::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'bio'         => 'nullable|string',
            'nationality' => 'nullable|string|max:255',
        ]);

        $author->update($validated);

        ActivityLogger::log('updated', 'authors', 'Updated author: ' . $author->name);

        return redirect()->route('admin.authors.index')->with('success', 'Author updated successfully.');
    }

    public function destroy($id)
    {
        $author = Author::findOrFail($id);

        ActivityLogger::log('deleted', 'authors', 'Deleted author: ' . $author->name);

        $author->delete();

        return redirect()->route('admin.authors.index')->with('success', 'Author deleted successfully.');
    }
}
