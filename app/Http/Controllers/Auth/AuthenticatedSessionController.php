<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Services\ActivityLogger;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Invalidate all other sessions for this user EXCEPT the current one
        $sessionId = $request->session()->getId();
        if ($sessionId) {
            DB::table('sessions')
                ->where('user_id', auth()->id())
                ->where('id', '!=', $sessionId)
                ->delete();
        }
        
        ActivityLogger::log('login', 'auth', 'User logged in successfully.');

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Log before actual logout to capture user context
        ActivityLogger::log('logout', 'auth', 'User logged out.');

        // Clear remember token
        if (auth()->check()) {
            auth()->user()->forceFill([
                'remember_token' => null
            ])->save();
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
