<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TwoFactorAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->two_factor_enabled) {
            // Check if current session has confirmed 2FA
            if (! session('2fa_authenticated')) {
                // Prevent redirect loop if already on the 2fa form or submitting it
                if (! $request->routeIs('2fa.challenge') && ! $request->routeIs('2fa.verify') && ! $request->routeIs('2fa.resend')) {
                    return redirect()->route('2fa.challenge');
                }
            }
        }

        return $next($request);
    }
}
