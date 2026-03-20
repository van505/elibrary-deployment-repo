<?php

namespace App\Http\Controllers\Member;

use App\Models\Category;
use App\Models\Ebook;
use App\Models\EbookAccess;

class EbookController extends BaseMemberController
{
    public function index()
    {
        $categories = Category::orderBy('name')->get();
        $member     = $this->getOrCreateMember();

        $query = Ebook::with('authors', 'category');

        if (request('search')) {
            $search = request('search');
            $query->where('title', 'like', '%' . $search . '%')
                  ->orWhereHas('authors', fn ($a) => $a->where('name', 'like', '%' . $search . '%'));
        }

        if (request('category_id')) {
            $query->where('category_id', request('category_id'));
        }

        if (request('access_level')) {
            $query->where('access_level', request('access_level'));
        }

        $ebooks = $query->paginate(12);

        $accessedIds = $member->ebookAccess()->pluck('ebook_id')->toArray();

        return view('member.ebooks.index', compact('ebooks', 'categories', 'accessedIds', 'member'));
    }

    public function show($id)
    {
        $ebook = Ebook::with('authors', 'category', 'reviews.member.user')->findOrFail($id);

        $approvedReviews = $ebook->reviews->where('status', 'approved');

        $member    = $this->getOrCreateMember();
        $hasAccess = EbookAccess::where('member_id', $member->id)
            ->where('ebook_id', $id)
            ->exists();

        return view('member.ebooks.show', compact('ebook', 'approvedReviews', 'hasAccess', 'member'));
    }
}
