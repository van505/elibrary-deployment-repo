<?php

namespace App\Helpers;

use App\Models\ActivityLog;

class ActivityLogger
{
    public static function log(string $action, string $module, string $description): void
    {
        if (auth()->check()) {
            ActivityLog::create([
                'user_id'     => auth()->id(),
                'action'      => $action,
                'module'      => $module,
                'description' => $description,
                'ip_address'  => request()->ip(),
            ]);
        }
    }
}
