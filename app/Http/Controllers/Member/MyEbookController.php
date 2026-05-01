<?php

namespace App\Http\Controllers\Member;

class MyEbookController extends BaseMemberController
{
    public function index()
    {
        $member = $this->getOrCreateMember();

        $accesses = $member->ebookAccess()
            ->with('ebook.authors')
            ->latest('accessed_at')
            ->paginate(12)->withQueryString();

        return view('member.my-ebooks', compact('accesses'));
    }

    public function destroy($id)
    {
        $member = $this->getOrCreateMember();
        $member->ebookAccess()->where('id', $id)->delete();
        
        return back()->with('success', 'Ebook removed from history.');
    }

    public function clear()
    {
        $member = $this->getOrCreateMember();
        $member->ebookAccess()->delete();
        
        return back()->with('success', 'Reading history cleared.');
    }
}
