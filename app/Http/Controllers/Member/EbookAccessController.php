<?php

namespace App\Http\Controllers\Member;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\Ebook;
use App\Models\EbookAccess;

class EbookAccessController extends Controller
{
    public function access(Ebook $ebook)
    {
        $member = auth()->user()->member;

        // Must have an active plan
        $plan = $member->currentPlan();
        if (! $plan) {
            return redirect()->route('member.subscriptions.index')
                ->with('error', 'You need an active subscription to access ebooks.');
        }

        // Check plan vs ebook access_level requirement
        $levels = ['free' => 0, 'basic' => 1, 'premium' => 2];
        if (($levels[$plan->slug] ?? 0) < ($levels[$ebook->access_level] ?? 0)) {
            return redirect()->route('member.subscriptions.index')
                ->with('error', 'Upgrade your plan to access this ebook.');
        }

        // Check ebook limit
        if (! $member->canAccessEbook($ebook->id)) {
            return redirect()->back()
                ->with('error', 'You have reached your plan ebook limit. Upgrade to access more.');
        }

        // Grant / update access
        EbookAccess::firstOrCreate(
            ['member_id' => $member->id, 'ebook_id' => $ebook->id],
            ['accessed_at' => now()]
        );

        ActivityLogger::log('accessed', 'ebooks', 'Member accessed ebook: ' . $ebook->title);

        return redirect()->route('member.ebooks.read', $ebook->id)
            ->with('success', 'Enjoy reading ' . $ebook->title . '!');
    }

    public function read(Ebook $ebook)
    {
        $member = auth()->user()->member;

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
        $member = auth()->user()->member;

        EbookAccess::where('member_id', $member->id)
            ->where('ebook_id', $ebook->id)
            ->delete();

        return redirect()->back()->with('success', 'Removed from your reading list.');
    }
}
