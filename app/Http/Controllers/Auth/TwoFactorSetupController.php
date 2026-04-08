<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class TwoFactorSetupController extends Controller
{
    /**
     * Enable email-based 2FA for the authenticated user.
     */
    public function enable(Request $request)
    {
        $request->validate(['password' => 'required|current_password']);

        $user = auth()->user();

        if ($user->two_factor_enabled) {
            return back()->with('info', 'Two-Factor Authentication is already enabled.');
        }

        $user->forceFill(['two_factor_enabled' => true])->save();

        ActivityLogger::log('2fa_enabled', 'auth', 'Email-based 2FA was enabled.');

        return back()->with('success', 'Two-Factor Authentication (Email) has been enabled. You will receive a code by email on each login.');
    }

    /**
     * Disable email-based 2FA for the authenticated user.
     */
    public function disable(Request $request)
    {
        $request->validate(['password' => 'required|current_password']);

        $user = auth()->user();
        $user->forceFill([
            'two_factor_enabled'   => false,
            'google2fa_secret'     => null,
            'email_otp_code'       => null,
            'email_otp_expires_at' => null,
        ])->save();

        ActivityLogger::log('2fa_disabled', 'auth', 'Email-based 2FA was disabled.');

        return back()->with('success', 'Two-Factor Authentication has been disabled.');
    }
}
