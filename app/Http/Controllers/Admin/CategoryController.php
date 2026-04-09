<?php

namespace App\Http\Controllers\Admin;

use App\Services\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($request->name);

        $category = Category::create($validated);

        ActivityLogger::log('created', 'categories', 'Created new category: ' . $category->name);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);

        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:categories,name,' . $id,
            'description' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($request->name);

        $category->update($validated);

        ActivityLogger::log('updated', 'categories', 'Updated category: ' . $category->name);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        // Guard: prevent archiving if category has active ebooks
        if ($category->ebooks()->exists()) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Cannot archive this category because it has active ebooks. Remove or reassign the ebooks first.');
        }

        ActivityLogger::log('deleted', 'categories', 'Archived category: ' . $category->name);

        $category->delete(); // soft delete

        return redirect()->route('admin.categories.index')->with('success', 'Category archived successfully.');
    }
}
