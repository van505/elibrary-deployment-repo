<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SessionTimeout
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            $lastActivity = session('last_activity_time');
            
            // Allow 60 mins for admin, 30 mins for others
            $timeout = auth()->user()->role === 'admin' ? 3600 : 1800;

            if ($lastActivity && (time() - $lastActivity > $timeout)) {
                // Remove remember_token for extra security when kicking out
                auth()->user()->forceFill([
                    'remember_token' => null
                ])->save();

                auth()->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->withErrors([
                    'email' => 'Your session has expired. Please login again.'
                ]);
            }
            
            session(['last_activity_time' => time()]);
        }

        return $next($request);
    }
}
