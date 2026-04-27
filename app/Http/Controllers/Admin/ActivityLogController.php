<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use App\Traits\HandlesAdminFilters;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    use HandlesAdminFilters;

    public function index(Request $request)
    {
        $query = ActivityLog::with('user');

        $query = $this->applyFilters(
            $query,
            $request,
            'filter_logs',
            ['description'], // searchable
            ['user_id', 'module', 'action'] // filterable
        );

        $logs = $query->paginate(15)->appends($request->query());

        // For dropdowns (could be optimized if tables get huge, but this works generally)
        $users = User::orderBy('first_name')->get();
        $modules = ActivityLog::select('module')->distinct()->pluck('module');
        $actions = ActivityLog::select('action')->distinct()->pluck('action');

        return view('admin.activity-logs.index', compact('logs', 'users', 'modules', 'actions'));
    }

    public function clear()
    {
        ActivityLog::truncate();
        return back()->with('success', 'All activity logs have been successfully cleared.');
    }
}
