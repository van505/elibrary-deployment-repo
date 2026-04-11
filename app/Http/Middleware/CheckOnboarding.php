<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckOnboarding
{
    /**
     * Routes that bypass the onboarding check.
     */
    protected array $bypassRoutes = [
        'member.onboarding.*',
        'logout',
        'member.profile.*',
        'profile.*',
        '2fa.*',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user && $user->role === 'member') {
            $member = $user->member;

            if ($member && !$member->onboarding_completed) {
                // Check if current route should bypass onboarding
                foreach ($this->bypassRoutes as $pattern) {
                    if ($request->routeIs($pattern)) {
                        return $next($request);
                    }
                }

                return redirect()->route('member.onboarding.show');
            }
        }

        return $next($request);
    }
}
