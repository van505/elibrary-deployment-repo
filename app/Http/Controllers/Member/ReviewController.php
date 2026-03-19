<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'ebook_id' => 'required|exists:ebooks,id',
            'rating'   => 'required|integer|min:1|max:5',
            'comment'  => 'nullable|string',
        ]);

        $member = auth()->user()->member;

        // Must have a returned borrowing for this ebook
        $hasBorrowed = $member->borrowings()
            ->where('ebook_id', $request->ebook_id)
            ->where('status', 'returned')
            ->exists();

        if (!$hasBorrowed) {
            return redirect()->back()->with('error', 'You can only review ebooks you have borrowed and returned.');
        }

        // No duplicate review
        $alreadyReviewed = $member->reviews()
            ->where('ebook_id', $request->ebook_id)
            ->exists();

        if ($alreadyReviewed) {
            return redirect()->back()->with('error', 'You have already submitted a review for this ebook.');
        }

        Review::create([
            'member_id' => $member->id,
            'ebook_id'  => $request->ebook_id,
            'rating'    => $request->rating,
            'comment'   => $request->comment,
            'status'    => 'pending',
        ]);

        return redirect()->back()->with('success', 'Review submitted and pending approval.');
    }

    public function destroy($id)
    {
        $member = auth()->user()->member;
        $review = Review::where('id', $id)
            ->where('member_id', $member->id)
            ->firstOrFail();

        $review->delete();

        return redirect()->back()->with('success', 'Review deleted successfully.');
    }
}
