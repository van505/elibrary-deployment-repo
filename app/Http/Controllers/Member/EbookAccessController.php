<?php

namespace App\Http\Controllers\Member;

use App\Helpers\ActivityLogger;
use App\Models\Ebook;
use App\Models\EbookAccess;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

        // Resolve a direct URL for the file (public disk only — no streaming)
        // If the file is in the public disk, serve it directly via asset URL
        $fileUrl = null;

        if (Storage::disk('public')->exists($ebook->file_path)) {
            $fileUrl = Storage::disk('public')->url($ebook->file_path);
        } elseif (Storage::disk('public')->exists('ebooks/' . basename($ebook->file_path))) {
            $fileUrl = Storage::disk('public')->url('ebooks/' . basename($ebook->file_path));
        }

        // If file is on private disk, fall back to the stream route
        if (! $fileUrl && Storage::disk('private')->exists($ebook->file_path)) {
            $fileUrl = route('member.ebooks.stream', $ebook->id);
        }

        return view('member.ebooks.read', compact('ebook', 'fileUrl'));
    }

    public function stream(Ebook $ebook)
    {
        $member = $this->getOrCreateMember();

        $hasAccess = EbookAccess::where('member_id', $member->id)
            ->where('ebook_id', $ebook->id)
            ->exists();

        if (! $hasAccess) {
            abort(403, 'Unauthorized access to this resource.');
        }

        // Try private disk first, then fall back to public, then raw storage path
        $filePath = null;

        if (Storage::disk('private')->exists($ebook->file_path)) {
            $disk = 'private';
            $filePath = Storage::disk('private')->path($ebook->file_path);
        } elseif (Storage::disk('public')->exists($ebook->file_path)) {
            $disk = 'public';
            $filePath = Storage::disk('public')->path($ebook->file_path);
        } else {
            // Last resort: try absolute path under storage/app/
            $absolute = storage_path('app/' . $ebook->file_path);
            if (file_exists($absolute)) {
                $filePath = $absolute;
                $disk = null;
            }
        }

        if (! $filePath || ! file_exists($filePath)) {
            abort(404, 'Ebook file not found. Please contact the administrator.');
        }

        $contentType = match($ebook->file_type) {
            'mp3'  => 'audio/mpeg',
            'epub' => 'application/epub+zip',
            default => 'application/pdf',
        };

        $headers = [
            'Content-Type'        => $contentType,
            'Content-Disposition' => 'inline; filename="' . Str::slug($ebook->title) . '.' . $ebook->file_type . '"',
            'Cache-Control'       => 'no-store, no-cache, must-revalidate',
            'Pragma'              => 'no-cache',
            'Expires'             => '0',
        ];

        // Use response()->file() — sends proper Content-Length headers
        // required by browsers to display PDFs inline in iframes
        return response()->file($filePath, $headers);
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
