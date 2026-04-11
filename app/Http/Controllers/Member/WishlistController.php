<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Ebook;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $member = auth()->user()->member;
        
        $wishlistedEbooks = $member->wishlistedEbooks()
            ->with(['authors', 'category'])
            ->latest('ebook_wishlists.created_at')
            ->paginate(12);

        return view('member.wishlist.index', compact('wishlistedEbooks'));
    }

    public function toggle(Ebook $ebook)
    {
        $member = auth()->user()->member;

        $exists = $member->wishlistedEbooks()->where('ebook_id', $ebook->id)->exists();

        if ($exists) {
            $member->wishlist()->where('ebook_id', $ebook->id)->delete();
            $message = 'Removed from wishlist.';
        } else {
            $member->wishlist()->create(['ebook_id' => $ebook->id]);
            $message = 'Added to wishlist.';
        }

        return back()->with('success', $message);
    }
}
