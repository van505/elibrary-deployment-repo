<?php

namespace App\Http\Controllers\Member;

use App\Helpers\ActivityLogger;
use App\Models\Ebook;
use App\Models\EbookAccess;

class EbookAccessController extends BaseMemberController
{
    public function access(Ebook $ebook)
    {
        $member = $this->getOrCreateMember();

        // Get active plan (guaranteed to exist after getOrCreateMember)
        $plan = $member->currentPlan();

        if (! $plan) {
            return redirect()->route('member.subscriptions.index')
                ->with('error', 'Please select a subscription plan first.');
        }

        // Check plan level vs ebook access level
        $levelMap  = ['free' => 0, 'basic' => 1, 'premium' => 2];
        $planLevel  = $levelMap[$plan->slug] ?? 0;
        $ebookLevel = $levelMap[$ebook->access_level] ?? 0;

        if ($planLevel < $ebookLevel) {
            return redirect()->route('member.subscriptions.index')
                ->with('error', 'Upgrade your plan to access this ebook.');
        }

        // Check ebook limit
        $limit            = $plan->ebook_limit;
        $currentCount     = $member->ebookAccess()->count();
        $alreadyHasAccess = $member->ebookAccess()->where('ebook_id', $ebook->id)->exists();

        if (! $alreadyHasAccess && $limit !== -1 && $currentCount >= $limit) {
            return redirect()->back()
                ->with('error', 'You have reached your plan limit of ' . $limit . ' ebooks. Please upgrade.');
        }

        // Grant access
        EbookAccess::firstOrCreate(
            ['member_id' => $member->id, 'ebook_id' => $ebook->id],
            ['accessed_at' => now()]
        );

        ActivityLogger::log('accessed', 'ebooks', 'Accessed ebook: ' . $ebook->title);

        return redirect()->route('member.ebooks.read', $ebook->id)
            ->with('success', 'Enjoy reading ' . $ebook->title . '!');
    }

    public function read(Ebook $ebook)
    {
        $member = $this->getOrCreateMember();

        $hasAccess = EbookAccess::where('member_id', $member->id)
            ->where('ebook_id', $ebook->id)
            ->exists();

        if (! $hasAccess) {
            return redirect()->route('member.ebooks.show', $ebook->id)
                ->with('error', 'You do not have access to this ebook.');
        }

        return view('member.ebooks.read', compact('ebook'));
    }

    public function removeAccess(Ebook $ebook)
    {
        $member = $this->getOrCreateMember();

        EbookAccess::where('member_id', $member->id)
            ->where('ebook_id', $ebook->id)
            ->delete();

        return redirect()->back()->with('success', 'Removed from your reading list.');
    }
}
