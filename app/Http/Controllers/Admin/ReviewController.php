<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $status  = request('status', 'all');
        $query   = Review::with('member.user', 'ebook');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $reviews = $query->paginate(10);

        return view('admin.reviews.index', compact('reviews', 'status'));
    }

    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $review->update($validated);

        ActivityLogger::log('updated', 'reviews', 'Review ID: ' . $id . ' status set to: ' . $validated['status']);

        return redirect()->route('admin.reviews.index')->with('success', 'Review status updated successfully.');
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);

        ActivityLogger::log('deleted', 'reviews', 'Deleted review ID: ' . $id);

        $review->delete();

        return redirect()->route('admin.reviews.index')->with('success', 'Review deleted successfully.');
    }
}
