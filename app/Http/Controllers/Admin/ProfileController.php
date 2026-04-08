<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\PasswordHistory;
use App\Services\ActivityLogger;
use App\Mail\PasswordChangedNotification;
use Illuminate\Support\Facades\Mail;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('admin.profile', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'first_name'              => 'nullable|string|max:100',
            'last_name'               => 'nullable|string|max:100',
            'email'                   => 'required|email|unique:users,email,' . $user->id,
            'current_password'        => [
                'required',
                function ($attribute, $value, $fail) use ($user) {
                    if (! Hash::check($value, $user->password)) {
                        $fail('The current password is incorrect.');
                    }
                },
            ],
            'new_password'            => ['nullable', 'confirmed', Password::defaults()],
            'new_password_confirmation' => 'nullable',
        ]);

        // Update basic info
        $user->first_name = $request->first_name;
        $user->last_name  = $request->last_name;
        $user->email      = $request->email;

        if ($request->filled('new_password')) {
            // 1. Check new password is not the same as current
            if (Hash::check($request->new_password, $user->password)) {
                return back()->withErrors(['new_password' => 'New password cannot be the same as your current password.'])->withInput();
            }

            // 2. Check against last 3 password history entries
            $recentPasswords = PasswordHistory::where('user_id', $user->id)
                ->latest()
                ->limit(3)
                ->get();

            foreach ($recentPasswords as $old) {
                if (Hash::check($request->new_password, $old->password)) {
                    return back()->withErrors(['new_password' => 'You cannot reuse your last 3 passwords. Please choose a different password.'])->withInput();
                }
            }

            // 3. Save CURRENT password to history BEFORE changing
            PasswordHistory::create([
                'user_id'  => $user->id,
                'password' => $user->password, // save the current hashed password
            ]);

            // 4. Trim history — keep only last 5 entries (MySQL requires LIMIT with OFFSET)
            $keepIds = PasswordHistory::where('user_id', $user->id)
                ->latest()
                ->limit(5)
                ->pluck('id');
            PasswordHistory::where('user_id', $user->id)
                ->whereNotIn('id', $keepIds)
                ->delete();

            // 5. Update password
            $user->password = Hash::make($request->new_password);

            ActivityLogger::log('password_changed', 'auth', 'Admin password was successfully changed.');
            Mail::to($user->email)->send(new PasswordChangedNotification($user));
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }
}
