<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;

class ArchiveController extends BaseMemberController
{
    public function index()
    {
        // For now, this is a placeholder page since the specific scope
        // of the archive (reading history, reviews, etc.) wasn't fully detailed.
        // It provides the required premium UI shell for future functionality.
        return view('member.archive.index');
    }
}
