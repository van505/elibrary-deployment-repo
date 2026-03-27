<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->keyBy('key');
        $plans    = SubscriptionPlan::orderBy('name')->get();
        return view('admin.settings.index', compact('settings', 'plans'));
    }

    public function update(Request $request)
    {
        // Handle logo upload separately
        if ($request->hasFile('site_logo')) {
            $path = $request->file('site_logo')->store('settings', 'public');
            Setting::where('key', 'site_logo')->update(['value' => $path]);
        }

        // Handle all other settings
        $inputs = $request->except(['_method', '_token', 'site_logo']);

        foreach ($inputs as $key => $value) {
            Setting::where('key', $key)->update(['value' => $value]);
        }

        // Handle boolean fields that are unchecked (checkboxes don't submit when unchecked)
        foreach (['allow_registration', 'maintenance_mode'] as $boolKey) {
            if (! $request->has($boolKey)) {
                Setting::where('key', $boolKey)->update(['value' => '0']);
            }
        }

        ActivityLogger::log('updated', 'settings', 'Updated system settings.');

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
