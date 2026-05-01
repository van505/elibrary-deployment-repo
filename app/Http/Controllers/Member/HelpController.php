<?php

namespace App\Http\Controllers\Member;

class HelpController extends BaseMemberController
{
    public function index()
    {
        return view('member.help.index');
    }
}
