<?php

namespace App\Http\Controllers\Member;

use App\Services\ActivityLogger;
use App\Models\Ebook;
use App\Models\EbookAccess;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;

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

        // Check subscription level against ebook access level
        $levelMap   = ['free' => 0, 'basic' => 1, 'premium' => 2];
        $plan       = $member->currentPlan();
        $planLevel  = $plan ? ($levelMap[$plan->slug] ?? 0) : -1;
        $ebookLevel = $levelMap[$ebook->access_level] ?? 0;
        $hasSubscriptionAccess = ($planLevel >= $ebookLevel);

        // Preview mode: member doesn't have full access but requests ?preview=true
        $isPreview    = false;
        $previewPages = (int) ($ebook->preview_pages ?? 10);

        if (! $hasAccess || ! $hasSubscriptionAccess) {
            if (request()->boolean('preview') && $previewPages > 0) {
                $isPreview = true;
            } else {
                return redirect()->route('member.ebooks.show', $ebook->id)
                    ->with('info', $previewPages > 0
                        ? "You can preview the first {$previewPages} pages. Subscribe to read the full book."
                        : 'You do not have access to this ebook.');
            }
        }

        // ─── SECURE FILE URL ──────────────────────────────────────────────────
        // NEVER expose direct Storage URLs — always route through the authenticated
        // stream endpoints so access checks are enforced on every file request.

        if ($isPreview) {
            // Generate a signed, short-lived URL for the preview stream
            // The previewStream endpoint will serve only the first N pages via FPDI
            $fileUrl = URL::signedRoute(
                'member.ebooks.preview-stream',
                ['ebook' => $ebook->id],
                now()->addMinutes(30)
            );
        } else {
            // Full read — always use the authenticated stream route
            $fileUrl = route('member.ebooks.stream', $ebook->id);
        }

        // Update Reading Streak (only on real reads, not previews)
        if (! $isPreview) {
            \App\Services\StreakService::updateStreak($member);
        }

        return view('member.ebooks.read', compact('ebook', 'fileUrl', 'isPreview', 'previewPages'));
    }

    /**
     * Secure streaming endpoint for full-access reads.
     * Validates subscription level (not just EbookAccess row) before serving the file.
     */
    public function stream(Ebook $ebook)
    {
        $member = $this->getOrCreateMember();

        // Check both EbookAccess AND subscription level
        $hasAccess = EbookAccess::where('member_id', $member->id)
            ->where('ebook_id', $ebook->id)
            ->exists();

        $levelMap   = ['free' => 0, 'basic' => 1, 'premium' => 2];
        $plan       = $member->currentPlan();
        $planLevel  = $plan ? ($levelMap[$plan->slug] ?? 0) : -1;
        $ebookLevel = $levelMap[$ebook->access_level] ?? 0;

        if (! $hasAccess || $planLevel < $ebookLevel) {
            abort(403, 'Unauthorized access to this resource.');
        }

        $filePath = $this->resolveFilePath($ebook);

        if (! $filePath) {
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

        return response()->file($filePath, $headers);
    }

    /**
     * Secure preview streaming endpoint.
     * Serves only the first N pages of a PDF using FPDI — the full PDF is never sent.
     * Requires a valid signed URL (generated by read() method).
     */
    public function previewStream(Ebook $ebook)
    {
        // Validate the signed URL — rejects any direct/modified URL access
        if (! request()->hasValidSignature()) {
            abort(403, 'This preview link has expired or is invalid. Please return to the ebook page.');
        }

        $member = $this->getOrCreateMember();

        // Validate preview eligibility: must NOT have full subscription access
        $levelMap   = ['free' => 0, 'basic' => 1, 'premium' => 2];
        $plan       = $member->currentPlan();
        $planLevel  = $plan ? ($levelMap[$plan->slug] ?? 0) : -1;
        $ebookLevel = $levelMap[$ebook->access_level] ?? 0;
        $previewPages = (int) ($ebook->preview_pages ?? 10);

        // If they somehow have full access AND call this preview endpoint, just redirect
        if ($planLevel >= $ebookLevel && EbookAccess::where('member_id', $member->id)->where('ebook_id', $ebook->id)->exists()) {
            return redirect()->route('member.ebooks.read', $ebook->id);
        }

        // Preview not configured
        if ($previewPages <= 0) {
            abort(403, 'No preview available for this ebook.');
        }

        // Only PDFs can be page-sliced — for EPUB/MP3 deny download but allow inline read
        if ($ebook->file_type !== 'pdf') {
            abort(403, 'Preview is only available for PDF ebooks. Please subscribe to read this format.');
        }

        $filePath = $this->resolveFilePath($ebook);

        if (! $filePath) {
            abort(404, 'Ebook file not found. Please contact the administrator.');
        }

        // ─── FPDI: Extract only the first N pages ────────────────────────────
        try {
            $pdf = new \setasign\Fpdi\Fpdi();
            $pdf->setSourceFile($filePath);

            $totalPages = $pdf->setSourceFile($filePath); // returns total page count
            $pagesToServe = min($previewPages, $totalPages);

            for ($i = 1; $i <= $pagesToServe; $i++) {
                $tplId = $pdf->importPage($i);
                $size  = $pdf->getTemplateSize($tplId);

                // Add page with the same dimensions as the source
                $pdf->AddPage($size['width'] > $size['height'] ? 'L' : 'P', [$size['width'], $size['height']]);
                $pdf->useTemplate($tplId);
            }

            $pdfContent = $pdf->Output('S'); // 'S' = return as string

        } catch (\Exception $e) {
            // FPDI can fail on encrypted/malformed PDFs — fall back to blocked access
            abort(422, 'This PDF cannot be previewed. It may be encrypted or use an unsupported format.');
        }

        return response($pdfContent, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="preview-' . Str::slug($ebook->title) . '.pdf"',
            'Content-Length'      => strlen($pdfContent),
            'Cache-Control'       => 'no-store, no-cache, must-revalidate, private',
            'Pragma'              => 'no-cache',
            'Expires'             => '0',
            // Prevent the browser from offering "Save" on this response
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function removeAccess(Ebook $ebook)
    {
        $member = $this->getOrCreateMember();

        EbookAccess::where('member_id', $member->id)
            ->where('ebook_id', $ebook->id)
            ->delete();

        return redirect()->back()->with('success', 'Removed from your reading list.');
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    /**
     * Resolves the absolute file path for an ebook from either disk.
     * Returns null if not found.
     */
    private function resolveFilePath(Ebook $ebook): ?string
    {
        if (Storage::disk('private')->exists($ebook->file_path)) {
            return Storage::disk('private')->path($ebook->file_path);
        }

        if (Storage::disk('public')->exists($ebook->file_path)) {
            return Storage::disk('public')->path($ebook->file_path);
        }

        if (Storage::disk('public')->exists('ebooks/' . basename($ebook->file_path))) {
            return Storage::disk('public')->path('ebooks/' . basename($ebook->file_path));
        }

        $absolute = storage_path('app/' . $ebook->file_path);
        if (file_exists($absolute)) {
            return $absolute;
        }

        return null;
    }
}
