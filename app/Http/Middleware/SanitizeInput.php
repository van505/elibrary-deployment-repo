<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SanitizeInput
{
    protected array $except = [
        'password',
        'password_confirmation',
        'current_password',
        'new_password',
        'new_password_confirmation',
        'book_description',
        'description', // often used for rich text
    ];

    public function handle(Request $request, Closure $next)
    {
        $input = $request->all();

        array_walk_recursive($input, function (&$value, $key) {
            // Check if key is in the exception list
            if (in_array($key, $this->except)) {
                return;
            }

            if (is_string($value)) {
                // Strip HTML tags
                $value = strip_tags($value);
                // Trim whitespace
                $value = trim($value);
            }
        });

        $request->merge($input);

        return $next($request);
    }
}
