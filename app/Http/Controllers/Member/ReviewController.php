<?php

namespace App\Http\Controllers\Member;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends BaseMemberController
{
    public function index()
    {
        $member = $this->getOrCreateMember();

        $reviews = $member->reviews()
            ->with('ebook')
            ->latest()
            ->paginate(10);

        return view('member.reviews.index', compact('reviews'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ebook_id' => 'required|exists:ebooks,id',
            'rating'   => 'required|integer|min:1|max:5',
            'comment'  => 'nullable|string|max:1000',
        ]);

        $member = $this->getOrCreateMember();

        // No duplicate reviews
        $alreadyReviewed = Review::where('member_id', $member->id)
            ->where('ebook_id', $request->ebook_id)
            ->exists();

        if ($alreadyReviewed) {
            return redirect()->back()
                ->with('error', 'You have already reviewed this ebook.');
        }

        Review::create([
            'member_id' => $member->id,
            'ebook_id'  => $request->ebook_id,
            'rating'    => $request->rating,
            'comment'   => $request->comment,
            'status'    => 'pending',
        ]);

        return redirect()->back()->with('success', 'Review submitted! Pending admin approval.');
    }

    public function destroy($id)
    {
        $member = $this->getOrCreateMember();
        $review = Review::where('id', $id)
            ->where('member_id', $member->id)
            ->firstOrFail();

        $review->delete();

        return redirect()->back()->with('success', 'Review deleted successfully.');
    }
}
