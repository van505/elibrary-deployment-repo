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
            ->paginate(12);

        return view('member.my-ebooks', compact('accesses'));
    }
}
