<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends BaseMemberController
{
    public function edit()
    {
        $member = $this->getOrCreateMember();
        return view('member.profile', compact('member'));
    }

    public function update(Request $request)
    {
        $member = $this->getOrCreateMember();

        $request->validate([
            'first_name'           => 'required|string|max:100',
            'middle_name'          => 'nullable|string|max:100',
            'last_name'            => 'required|string|max:100',
            'phone'                => 'nullable|string|max:30',
            'address'              => 'nullable|string|max:500',
            'current_password'     => 'nullable|string',
            'new_password'         => 'nullable|min:8|confirmed',
            'avatar'               => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        // ── Avatar Upload ──────────────────────────────────────────────────────
        if ($request->hasFile('avatar')) {
            // Delete old avatar from storage if it exists
            if ($member->avatar && Storage::disk('public')->exists($member->avatar)) {
                Storage::disk('public')->delete($member->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $member->avatar = $path;
        }

        $member->update([
            'first_name'  => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name'   => $request->last_name,
            'phone'       => $request->phone,
            'address'     => $request->address,
            'avatar'      => $member->avatar, // persists whether changed or not
        ]);

        // ── Password Change ────────────────────────────────────────────────────
        if ($request->filled('new_password')) {
            if (! $request->filled('current_password')) {
                return back()->withErrors(['current_password' => 'Enter your current password to change it.'])->withInput();
            }
            if (! Hash::check($request->current_password, auth()->user()->password)) {
                return back()->withErrors(['current_password' => 'The current password is incorrect.'])->withInput();
            }
            auth()->user()->update(['password' => Hash::make($request->new_password)]);
        }

        return back()->with('success', 'Profile updated successfully.');
    }
}
