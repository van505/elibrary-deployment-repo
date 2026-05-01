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
            ->with('ebook.authors')
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

        $review = Review::create([
            'member_id' => $member->id,
            'ebook_id'  => $request->ebook_id,
            'rating'    => $request->rating,
            'comment'   => $request->comment,
            'status'    => 'pending',
        ]);

        \App\Models\AdminNotification::create([
            'type' => 'new_review',
            'message' => ($member->first_name ? $member->full_name : $member->user->email) . " submitted a review for '{$review->ebook->title}' — awaiting approval.",
            'action_url' => route('admin.reviews.index'),
        ]);

        return redirect()->back()->with('success', 'Your review has been submitted and is awaiting approval.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'rating'   => 'required|integer|min:1|max:5',
            'comment'  => 'nullable|string|max:1000',
        ]);

        $member = $this->getOrCreateMember();
        $review = Review::where('id', $id)
            ->where('member_id', $member->id)
            ->firstOrFail();

        $review->update([
            'rating'  => $request->rating,
            'comment' => $request->comment,
            'status'  => 'pending', // Re-evaluate on edit
        ]);

        return redirect()->back()->with('success', 'Your review has been updated and is awaiting approval.');
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
