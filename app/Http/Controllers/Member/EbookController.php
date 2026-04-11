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

        $query = Ebook::with('authors', 'category', 'tags');

        if (request('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('isbn', 'like', '%' . $search . '%')
                  ->orWhereHas('authors', fn ($a) => $a->where('first_name', 'like', '%' . $search . '%')
                                                        ->orWhere('last_name', 'like', '%' . $search . '%'));
            });
        }

        if (request('category_id')) {
            $query->where('category_id', request('category_id'));
        }

        if (request('access_level')) {
            $query->where('access_level', request('access_level'));
        }

        if (request('tag')) {
            $query->whereHas('tags', function ($q) {
                $q->where('tag_name', request('tag'));
            });
        }

        $ebooks      = $query->paginate(12);
        $accessedIds = $member->ebookAccess()->pluck('ebook_id')->toArray();

        return view('member.ebooks.index', compact('ebooks', 'categories', 'accessedIds', 'member'));
    }

    public function show(Ebook $ebook)
    {
        $member = $this->getOrCreateMember();

        $ebook->load('authors', 'category', 'tags');

        $hasAccess = \App\Models\EbookAccess::where('member_id', $member->id)
            ->where('ebook_id', $ebook->id)
            ->exists();

        $isBookmarked = \App\Models\EbookBookmark::where('member_id', $member->id)
            ->where('ebook_id', $ebook->id)
            ->exists();

        $reviews = Review::where('ebook_id', $ebook->id)
            ->where('status', 'approved')
            ->with('member.user')
            ->latest()
            ->get();

        // Member's own pending reviews for this ebook (only shown to that member)
        $pendingReviews = Review::where('ebook_id', $ebook->id)
            ->where('member_id', $member->id)
            ->where('status', 'pending')
            ->latest()
            ->get();

        // Fetch related ebooks
        $authorIds = $ebook->authors->pluck('id');
        
        $sameCatSameAuthor = Ebook::with('authors', 'category')->where('id', '!=', $ebook->id)
            ->where('category_id', $ebook->category_id)
            ->whereHas('authors', fn($q) => $q->whereIn('authors.id', $authorIds))
            ->latest()->get();

        $sameAuthor = Ebook::with('authors', 'category')->where('id', '!=', $ebook->id)
            ->whereHas('authors', fn($q) => $q->whereIn('authors.id', $authorIds))
            ->whereNotIn('id', $sameCatSameAuthor->pluck('id'))
            ->latest()->get();
            
        $sameCat = Ebook::with('authors', 'category')->where('id', '!=', $ebook->id)
            ->where('category_id', $ebook->category_id)
            ->whereNotIn('id', $sameCatSameAuthor->pluck('id')->merge($sameAuthor->pluck('id')))
            ->latest()->get();

        $relatedEbooks = $sameCatSameAuthor->concat($sameAuthor)->concat($sameCat)->take(4);

        $isWishlisted = $member->wishlist()->where('ebook_id', $ebook->id)->exists();

        // -- Preview / read access logic --
        $levelMap   = ['free' => 0, 'basic' => 1, 'premium' => 2];
        $plan       = $member->currentPlan();
        $planLevel  = $plan ? ($levelMap[$plan->slug] ?? 0) : -1;
        $ebookLevel = $levelMap[$ebook->access_level] ?? 0;

        // Does the member's current plan COVER this ebook's access level?
        $planCanAccess = ($planLevel >= $ebookLevel);

        // canRead: plan covers it AND member has formally added it to their reading list
        $canRead = $planCanAccess && $hasAccess;

        // canPreview (with upgrade prompt): ONLY when the plan is INSUFFICIENT
        $previewPages = (int) ($ebook->preview_pages ?? 10);
        $canPreview   = !$planCanAccess && $previewPages > 0;

        return view('member.ebooks.show', compact(
            'ebook',
            'hasAccess',
            'isBookmarked',
            'isWishlisted',
            'reviews',
            'pendingReviews',
            'relatedEbooks',
            'canRead',
            'canPreview',
            'planCanAccess',
            'previewPages'
        ));
    }
}
