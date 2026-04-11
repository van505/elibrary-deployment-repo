<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Category;
use App\Models\Ebook;
use App\Models\EbookTag;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function suggestions(Request $request)
    {
        $query = $request->query('q');
        
        if (!$query || strlen($query) < 2) {
            return response()->json([
                'ebooks' => [],
                'authors' => [],
                'categories' => [],
                'tags' => []
            ]);
        }

        // Determine if this is an admin search
        $isAdminSearch = $request->boolean('admin') && auth()->check() && auth()->user()->role === 'admin';

        // Base URL helper
        // Using paths instead of full URLs ensures cleaner structure, but full works too.
        
        // 1. Ebooks
        $ebooksQuery = Ebook::query()->where('title', 'like', "%{$query}%");
        $ebookResults = $ebooksQuery->take(5)->get()->map(function ($ebook) use ($isAdminSearch) {
            $url = $isAdminSearch 
                ? route('admin.ebooks.edit', $ebook->id)
                : route('member.ebooks.show', $ebook->id); // Note: guest links are handled by the frontend specifically.
                
            return [
                'id' => $ebook->id,
                'title' => $ebook->title,
                'cover' => $ebook->cover_image ? \Storage::url($ebook->cover_image) : null,
                'access_level' => $ebook->access_level,
                'url' => $url
            ];
        });

        // 2. Authors
        $authorsQuery = Author::where('first_name', 'like', "%{$query}%")
            ->orWhere('last_name', 'like', "%{$query}%");
            
        $authorResults = $authorsQuery->take(3)->get()->map(function ($author) use ($isAdminSearch) {
            return [
                'id' => $author->id,
                'full_name' => collect([$author->first_name, $author->last_name])->filter()->join(' '),
                // Note: assuming admin.authors.edit is available. For users, they probably search by author in ebooks index
                'url' => $isAdminSearch ? route('admin.authors.edit', $author->id) : route('member.ebooks.index', ['search' => collect([$author->first_name, $author->last_name])->filter()->join(' ')])
            ];
        });

        // 3. Categories
        $categoriesQuery = Category::where('name', 'like', "%{$query}%");
        $categoryResults = $categoriesQuery->take(3)->get()->map(function ($category) use ($isAdminSearch) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'url' => $isAdminSearch ? route('admin.categories.edit', $category->id) : route('member.ebooks.index', ['category_id' => $category->id])
            ];
        });

        // 4. Tags
        $tagsQuery = EbookTag::select('tag_name')
            ->where('tag_name', 'like', "%{$query}%")
            ->distinct();
        $tagResults = $tagsQuery->take(3)->get()->map(function ($tag) use ($isAdminSearch) {
            return [
                'tag_name' => $tag->tag_name,
                // Tags probably only filter ebooks index
                'url' => $isAdminSearch ? route('admin.ebooks.index', ['tag' => $tag->tag_name]) : route('member.ebooks.index', ['tag' => $tag->tag_name])
            ];
        });

        return response()->json([
            'ebooks' => $ebookResults,
            'authors' => $authorResults,
            'categories' => $categoryResults,
            'tags' => $tagResults
        ]);
    }
}
