<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\TwoFactorOtpMail;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TwoFactorController extends Controller
{
    /**
     * Show the 2FA challenge page and send a fresh OTP.
     */
    public function challenge()
    {
        $user = auth()->user();

        if (!$user || !$user->two_factor_enabled) {
            return redirect()->route('dashboard');
        }

        // Generate a 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store hashed OTP + expiry
        $user->forceFill([
            'email_otp_code' => bcrypt($otp),
            'email_otp_expires_at' => now()->addMinutes(10),
        ])->save();

        // Send OTP email
        Mail::to($user->email)->send(new TwoFactorOtpMail($user, $otp));

        return view('auth.two-factor-challenge', [
            'email' => $user->email,
        ]);
    }

    /**
     * Resend a fresh OTP (AJAX or form POST).
     */
    public function resend()
    {
        return $this->challenge();
    }

    /**
     * Verify the submitted 6-digit code.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|digits:6',
        ]);

        $user = auth()->user();

        // Check expiry
        if (!$user->email_otp_expires_at || now()->isAfter($user->email_otp_expires_at)) {
            return back()->withErrors(['code' => 'Your verification code has expired. Please request a new one.']);
        }

        // Check code
        if (!\Illuminate\Support\Facades\Hash::check($request->code, $user->email_otp_code)) {
            return back()->withErrors(['code' => 'Invalid verification code. Please try again.']);
        }

        // Clear OTP from DB (one-time use)
        $user->forceFill([
            'email_otp_code' => null,
            'email_otp_expires_at' => null,
        ])->save();

        session(['2fa_authenticated' => true]);

        ActivityLogger::log('2fa_verified', 'auth', 'Successfully verified 2FA email code.');

        return redirect()->intended(route('dashboard'));
    }
}
