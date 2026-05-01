<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Collection;

class CollectionController extends Controller
{
    public function index()
    {
        $query = Collection::active()->withCount('ebooks');

        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if (request('sort') === 'az') {
            $query->orderBy('name', 'asc');
        } else {
            $query->latest();
        }

        $collections = $query->paginate(12)->withQueryString();

        return view('member.collections.index', compact('collections'));
    }

    public function show(Collection $collection)
    {
        if (!$collection->is_active) {
            abort(404);
        }

        $ebooks = $collection->ebooks()
                             ->with(['authors', 'category'])
                             ->get();

        return view('member.collections.show', compact('collection', 'ebooks'));
    }
}
