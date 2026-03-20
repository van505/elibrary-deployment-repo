<?php

namespace App\Http\Controllers\Member;

use App\Models\EbookAccess;
use App\Models\Ebook;
use App\Models\Review;
use App\Models\Category;

class EbookController extends BaseMemberController
{
    public function index()
    {
        $categories = Category::orderBy('name')->get();
        $member     = $this->getOrCreateMember();

        $query = Ebook::with('authors', 'category');

        if (request('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhereHas('authors', fn ($a) => $a->where('name', 'like', '%' . $search . '%'));
            });
        }

        if (request('category_id')) {
            $query->where('category_id', request('category_id'));
        }

        if (request('access_level')) {
            $query->where('access_level', request('access_level'));
        }

        $ebooks      = $query->paginate(12);
        $accessedIds = $member->ebookAccess()->pluck('ebook_id')->toArray();

        return view('member.ebooks.index', compact('ebooks', 'categories', 'accessedIds', 'member'));
    }

    public function show(Ebook $ebook)
    {
        $member = $this->getOrCreateMember();

        $ebook->load('authors', 'category');

        $hasAccess = EbookAccess::where('member_id', $member->id)
            ->where('ebook_id', $ebook->id)
            ->exists();

        $hasReviewed = Review::where('member_id', $member->id)
            ->where('ebook_id', $ebook->id)
            ->exists();

        $reviews = Review::where('ebook_id', $ebook->id)
            ->where('status', 'approved')
            ->with('member.user')
            ->latest()
            ->get();

        return view('member.ebooks.show', compact(
            'ebook',
            'hasAccess',
            'hasReviewed',
            'reviews'
        ));
    }
}
