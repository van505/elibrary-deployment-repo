<?php

namespace App\Http\Controllers\Admin;

use App\Services\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        $members = Member::with('user')->paginate(10);
        return view('admin.members.index', compact('members'));
    }

    public function show($id)
    {
        $member = Member::with(
            'user',
            'subscriptions.plan',
            'ebookAccess.ebook.authors',
            'reviews.ebook'
        )->findOrFail($id);

        return view('admin.members.show', compact('member'));
    }

    public function edit($id)
    {
        $member = Member::with('user')->findOrFail($id);
        return view('admin.members.edit', compact('member'));
    }

    public function update(Request $request, $id)
    {
        $member = Member::findOrFail($id);

        $validated = $request->validate([
            'first_name'  => 'nullable|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name'   => 'nullable|string|max:255',
            'status'      => 'required|in:active,suspended,expired',
            'phone'       => 'nullable|string|max:255',
            'address'     => 'nullable|string',
        ]);

        $member->update($validated);

        ActivityLogger::log('updated', 'members', 'Updated member: ' . $member->full_name . ' (' . $member->member_code . ')');

        return redirect()->route('admin.members.index')->with('success', 'Member updated successfully.');
    }

    public function destroy($id)
    {
        $member = Member::findOrFail($id);
        ActivityLogger::log('deleted', 'members', 'Deleted member: ' . $member->member_code);
        $member->delete();
        return redirect()->route('admin.members.index')->with('success', 'Member deleted successfully.');
    }

    /**
     * Enhanced status toggle with suspension reason support.
     */
    public function toggleStatus(Request $request, $id)
    {
        $member = Member::findOrFail($id);

        $request->validate([
            'status'             => 'required|in:active,suspended',
            'suspension_reason'  => 'required_if:status,suspended|nullable|string|max:500',
        ]);

        $isSuspending = $request->status === 'suspended';

        $member->update([
            'status'            => $request->status,
            'suspension_reason' => $isSuspending ? $request->suspension_reason : null,
            'suspended_at'      => $isSuspending ? now() : null,
        ]);

        $logDesc = $isSuspending
            ? 'Suspended member: ' . $member->full_name . '. Reason: ' . ($request->suspension_reason ?? 'Not given')
            : 'Activated member: ' . $member->full_name;

        ActivityLogger::log(
            $isSuspending ? 'suspended' : 'activated',
            'members',
            $logDesc
        );

        return redirect()->back()->with('success', 'Member ' . $request->status . ' successfully.');
    }
}

