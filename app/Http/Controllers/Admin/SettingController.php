<?php

namespace App\Http\Controllers\Admin;

use App\Services\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index(Request $request)
    {
        $settings = Setting::all()->keyBy('key');
        $plans    = SubscriptionPlan::orderBy('name')->get();

        // --- Activity Logs Data ---
        $logsQuery = \App\Models\ActivityLog::with('user')->latest();
        $logs = $logsQuery->paginate(15, ['*'], 'logs_page')->appends($request->query());
        $users = \App\Models\User::orderBy('first_name')->get();
        $modules = \App\Models\ActivityLog::select('module')->distinct()->pluck('module');
        $actions = \App\Models\ActivityLog::select('action')->distinct()->pluck('action');

        // --- Archive Data ---
        $typeMap = [
            'members'            => \App\Models\Member::class,
            'ebooks'             => \App\Models\Ebook::class,
            'authors'            => \App\Models\Author::class,
            'categories'         => \App\Models\Category::class,
            'reviews'            => \App\Models\Review::class,
            'subscription-plans' => \App\Models\SubscriptionPlan::class,
        ];
        $type = request('type', 'members');
        if (!array_key_exists($type, $typeMap)) {
            $type = 'members';
        }
        $archiveQuery = $typeMap[$type]::onlyTrashed();
        $counts = [];
        foreach ($typeMap as $key => $model) {
            $counts[$key] = $model::onlyTrashed()->count();
        }
        $records = $archiveQuery->paginate(15, ['*'], 'archive_page')->appends($request->query());

        return view('admin.settings.index', compact(
            'settings', 'plans', 
            'logs', 'users', 'modules', 'actions', 
            'type', 'counts', 'records'
        ));
    }

    public function update(Request $request)
    {
        $booleanKeys = ['allow_registration'];

        // ── 1. Logo upload ────────────────────────────────────────────────────
        if ($request->hasFile('site_logo')) {
            $path = $request->file('site_logo')->store('settings', 'public');
            DB::table('settings')->where('key', 'site_logo')->update(['value' => $path, 'updated_at' => now()]);
        }

        // ── 2. Boolean toggles (checkbox absent = unchecked = '0') ───────────
        foreach ($booleanKeys as $key) {
            $value = $request->has($key) ? '1' : '0';
            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $value, 'updated_at' => now()]
            );
        }

        // ── 3. All other text/number settings (skip booleans & reserved keys) ─
        $skip = array_merge($booleanKeys, ['_token', '_method', 'site_logo']);
        foreach ($request->except($skip) as $key => $value) {
            if ($value === null || $value === '') {
                continue;
            }
            DB::table('settings')->where('key', $key)->update(['value' => $value, 'updated_at' => now()]);
        }

        ActivityLogger::log('updated', 'settings', 'System settings updated.');

        return redirect()->back()->with('success', 'Settings saved successfully.');
    }
}
