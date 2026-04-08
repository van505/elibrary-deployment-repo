<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use App\Models\PasswordHistory;
use App\Services\ActivityLogger;
use App\Mail\PasswordChangedNotification;
use Illuminate\Support\Facades\Mail;

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
            'new_password'         => ['nullable', 'confirmed', Password::defaults()],
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

            // 1. New password cannot match current password
            if (Hash::check($request->new_password, auth()->user()->password)) {
                return back()->withErrors(['new_password' => 'New password cannot be the same as your current password.'])->withInput();
            }

            // 2. Check against last 3 password history entries
            $recentPasswords = PasswordHistory::where('user_id', auth()->id())
                ->latest()
                ->limit(3)
                ->get();

            foreach ($recentPasswords as $old) {
                if (Hash::check($request->new_password, $old->password)) {
                    return back()->withErrors(['new_password' => 'You cannot reuse your last 3 passwords. Please choose a different password.'])->withInput();
                }
            }

            // 3. Save CURRENT password to history BEFORE changing it
            PasswordHistory::create([
                'user_id'  => auth()->id(),
                'password' => auth()->user()->password,
            ]);

            // 4. Trim history — keep only last 5 entries (MySQL requires LIMIT with OFFSET)
            $keepIds = PasswordHistory::where('user_id', auth()->id())
                ->latest()
                ->limit(5)
                ->pluck('id');
            PasswordHistory::where('user_id', auth()->id())
                ->whereNotIn('id', $keepIds)
                ->delete();

            // 5. Update password
            auth()->user()->update(['password' => Hash::make($request->new_password)]);

            ActivityLogger::log('password_changed', 'auth', 'Password was successfully changed.');
            Mail::to(auth()->user()->email)->send(new PasswordChangedNotification(auth()->user()));
        }

        return back()->with('success', 'Profile updated successfully.');
    }
}
