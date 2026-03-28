<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\MemberNotification;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $status = request('status', 'all');
        $query  = Review::with('member.user', 'ebook');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $reviews = $query->latest()->paginate(10);

        return view('admin.reviews.index', compact('reviews', 'status'));
    }

    public function update(Request $request, $id)
    {
        $review = Review::with('member', 'ebook')->findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $review->update($validated);

        // ── Trigger notification to member ────────────────────────────────────
        if ($review->member) {
            $ebookTitle = $review->ebook->title ?? 'an ebook';
            $message = $validated['status'] === 'approved'
                ? "✅ Your review for \"{$ebookTitle}\" has been approved and is now published!"
                : "❌ Your review for \"{$ebookTitle}\" was not approved.";

            MemberNotification::create([
                'member_id' => $review->member->id,
                'type'      => 'review_' . $validated['status'],
                'message'   => $message,
                'is_read'   => false,
            ]);
        }

        ActivityLogger::log('updated', 'reviews', 'Review ID: ' . $id . ' status set to: ' . $validated['status']);

        return redirect()->back()->with('success', 'Review status updated.');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action'     => 'required|in:approved,rejected',
            'review_ids' => 'required|array|min:1',
        ]);

        $reviews = Review::with('member', 'ebook')
            ->whereIn('id', $request->review_ids)
            ->get();

        foreach ($reviews as $review) {
            $review->update(['status' => $request->action]);

            if ($review->member) {
                $ebookTitle = $review->ebook->title ?? 'an ebook';
                $message = $request->action === 'approved'
                    ? "✅ Your review for \"{$ebookTitle}\" has been approved!"
                    : "❌ Your review for \"{$ebookTitle}\" was not approved.";

                MemberNotification::create([
                    'member_id' => $review->member->id,
                    'type'      => 'review_' . $request->action,
                    'message'   => $message,
                    'is_read'   => false,
                ]);
            }
        }

        ActivityLogger::log('bulk_update', 'reviews', 'Bulk ' . $request->action . ' for ' . count($request->review_ids) . ' reviews.');

        return redirect()->back()->with('success', count($request->review_ids) . ' reviews ' . $request->action . '.');
    }

    public function approve(Review $review)
    {
        $review->load('member', 'ebook');

        $review->update(['status' => 'approved']);

        if ($review->member) {
            $ebookTitle = $review->ebook->title ?? 'an ebook';
            MemberNotification::create([
                'member_id' => $review->member->id,
                'type'      => 'review_approved',
                'message'   => "✅ Your review for \"{$ebookTitle}\" has been approved and is now published!",
                'is_read'   => false,
            ]);
        }

        ActivityLogger::log('approved', 'reviews', 'Approved review ID ' . $review->id);

        return back()->with('success', 'Review approved successfully.');
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);

        ActivityLogger::log('deleted', 'reviews', 'Deleted review ID: ' . $id);

        $review->delete();

        return redirect()->route('admin.reviews.index')->with('success', 'Review deleted.');
    }
}
