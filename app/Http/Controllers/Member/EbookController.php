<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Ebook;

class EbookController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        $query = Ebook::with('author', 'category');

        if (request('search')) {
            $search = request('search');
            $query->where('title', 'like', '%' . $search . '%')
                  ->orWhereHas('author', function ($a) use ($search) {
                      $a->where('name', 'like', '%' . $search . '%');
                  });
        }

        if (request('category_id')) {
            $query->where('category_id', request('category_id'));
        }

        $ebooks = $query->paginate(12);

        return view('member.ebooks.index', compact('ebooks', 'categories'));
    }

    public function show($id)
    {
        $ebook = Ebook::with('author', 'category', 'reviews.member.user')->findOrFail($id);

        $approvedReviews = $ebook->reviews->where('status', 'approved');

        $member        = auth()->user()->member;
        $hasBorrowed   = $member
            ? $member->borrowings()->where('ebook_id', $id)->where('status', 'returned')->exists()
            : false;

        return view('member.ebooks.show', compact('ebook', 'approvedReviews', 'hasBorrowed'));
    }
}
