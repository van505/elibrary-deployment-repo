<?php

namespace App\Services;

use App\Models\ActivityLog;

class ActivityLogger
{
    public static function log(string $action, string $module, string $description): void
    {
        if (auth()->check()) {
            $userAgent = request()->userAgent() ?? 'Unknown';
            
            // Basic parsing to avoid heavy dependencies just for logs, or you can use jenssegers/agent if installed.
            // Using a simple regex to extract browser/platform, but normally we'd just log the user_agent string
            // and parse it visually or use a dedicated package.
            $browser = 'Unknown';
            if (preg_match('/(MSIE|Edge|Firefox|Chrome|Safari|Opera)/i', $userAgent, $matches)) {
                $browser = $matches[1];
            }
            
            $platform = 'Unknown';
            if (preg_match('/(Windows|Macintosh|Linux|Android|iOS)/i', $userAgent, $matches)) {
                $platform = $matches[1];
            }

            ActivityLog::create([
                'user_id'     => auth()->id(),
                'action'      => $action,
                'module'      => $module,
                'description' => $description,
                'ip_address'  => request()->ip(),
                'user_agent'  => $userAgent,
                'browser'     => $browser,
                'platform'    => $platform,
            ]);
        }
    }
}
