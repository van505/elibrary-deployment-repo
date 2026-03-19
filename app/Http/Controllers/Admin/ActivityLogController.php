<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    public function index()
    {
        $query = ActivityLog::with('user');

        if (request('user_id')) {
            $query->where('user_id', request('user_id'));
        }

        if (request('module')) {
            $query->where('module', request('module'));
        }

        if (request('action')) {
            $query->where('action', request('action'));
        }

        $logs = $query->latest('created_at')->paginate(15);

        return view('admin.activity-logs.index', compact('logs'));
    }
}
