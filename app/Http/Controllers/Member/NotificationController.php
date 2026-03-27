<?php

namespace App\Http\Controllers\Member;

use App\Models\MemberNotification;

class NotificationController extends BaseMemberController
{
    public function markRead($id)
    {
        $member = $this->getOrCreateMember();

        MemberNotification::where('id', $id)
            ->where('member_id', $member->id)
            ->update(['is_read' => true]);

        return redirect()->back();
    }

    public function markAllRead()
    {
        $member = $this->getOrCreateMember();

        MemberNotification::where('member_id', $member->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }
}
