<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('admin.profile', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'first_name'           => 'nullable|string|max:100',
            'last_name'            => 'nullable|string|max:100',
            'email'                => 'required|email|unique:users,email,' . $user->id,
            'current_password'     => 'required',
            'new_password'         => 'nullable|min:8|confirmed',
        ];

        $validated = $request->validate($rules);

        // Verify current password
        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.'])->withInput();
        }

        $user->first_name = $request->first_name;
        $user->last_name  = $request->last_name;
        $user->email      = $request->email;

        if ($request->filled('new_password')) {
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }
}
