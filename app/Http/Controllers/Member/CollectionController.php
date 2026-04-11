<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Collection;

class CollectionController extends Controller
{
    public function index()
    {
        $collections = Collection::active()
                        ->withCount('ebooks')
                        ->latest()
                        ->paginate(12);

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
