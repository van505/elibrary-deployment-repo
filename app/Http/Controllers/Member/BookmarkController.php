<?php

namespace App\Http\Controllers\Member;

use App\Models\EbookBookmark;
use App\Models\Ebook;

class BookmarkController extends BaseMemberController
{
    public function index()
    {
        $member = $this->getOrCreateMember();

        $bookmarks = EbookBookmark::where('member_id', $member->id)
            ->with('ebook.authors')
            ->latest('created_at')
            ->paginate(12);

        return view('member.bookmarks.index', compact('bookmarks'));
    }

    public function toggle(Ebook $ebook)
    {
        $member = $this->getOrCreateMember();

        $existing = EbookBookmark::where('member_id', $member->id)
            ->where('ebook_id', $ebook->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $msg = 'Removed from bookmarks.';
        } else {
            EbookBookmark::create([
                'member_id'  => $member->id,
                'ebook_id'   => $ebook->id,
                'created_at' => now(),
            ]);
            $msg = 'Added to bookmarks!';
        }

        return redirect()->back()->with('success', $msg);
    }
}
