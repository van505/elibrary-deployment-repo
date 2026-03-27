<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ActivityLogger;
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
     * Quick status toggle (activate / suspend)
     */
    public function toggleStatus(Request $request, $id)
    {
        $member = Member::findOrFail($id);
        $member->status = $member->status === 'active' ? 'suspended' : 'active';
        $member->save();

        ActivityLogger::log('updated', 'members', 'Toggled status of member: ' . $member->member_code . ' → ' . $member->status);

        return redirect()->back()->with('success', 'Member status updated to ' . $member->status . '.');
    }
}
