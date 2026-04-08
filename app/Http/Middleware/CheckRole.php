<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Services\ActivityLogger;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        if (auth()->user()->role !== $role) {
            ActivityLogger::log(
                'unauthorized_access',
                'security',
                'Attempted to access ' . $request->path() . ' without ' . $role . ' role.'
            );
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
