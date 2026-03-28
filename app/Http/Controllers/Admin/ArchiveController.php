<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Category;
use App\Models\Ebook;
use App\Models\Member;
use App\Models\Review;
use App\Models\SubscriptionPlan;

class ArchiveController extends Controller
{
    // Map of allowed type slugs → model classes
    private array $typeMap = [
        'members'            => Member::class,
        'ebooks'             => Ebook::class,
        'authors'            => Author::class,
        'categories'         => Category::class,
        'reviews'            => Review::class,
        'subscription-plans' => SubscriptionPlan::class,
    ];

    public function index()
    {
        $type = request('type', 'members');

        // Guard against invalid type
        if (! array_key_exists($type, $this->typeMap)) {
            $type = 'members';
        }

        // Counts per tab
        $counts = [];
        foreach ($this->typeMap as $key => $model) {
            $counts[$key] = $model::onlyTrashed()->count();
        }

        // Records for the active tab
        $records = $this->typeMap[$type]::onlyTrashed()->latest('deleted_at')->paginate(15);

        return view('admin.archive.index', compact('type', 'counts', 'records'));
    }

    public function restore(string $type, int $id)
    {
        if (! array_key_exists($type, $this->typeMap)) {
            abort(404);
        }

        $model = $this->typeMap[$type]::onlyTrashed()->findOrFail($id);
        $model->restore();

        ActivityLogger::log('restored', $type, "Restored {$type} ID: {$id}");

        return back()->with('success', ucfirst(str_replace('-', ' ', $type)) . ' restored successfully.');
    }

    public function forceDelete(string $type, int $id)
    {
        if (! array_key_exists($type, $this->typeMap)) {
            abort(404);
        }

        $model = $this->typeMap[$type]::onlyTrashed()->findOrFail($id);
        $model->forceDelete();

        ActivityLogger::log('force_deleted', $type, "Permanently deleted {$type} ID: {$id}");

        return back()->with('success', ucfirst(str_replace('-', ' ', $type)) . ' permanently deleted.');
    }
}
