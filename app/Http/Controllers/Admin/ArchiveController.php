<?php

namespace App\Http\Controllers\Admin;

use App\Services\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Category;
use App\Models\Ebook;
use App\Models\Member;
use App\Models\Review;
use App\Models\SubscriptionPlan;
use App\Traits\HandlesAdminFilters;
use Illuminate\Http\Request;

class ArchiveController extends Controller
{
    use HandlesAdminFilters;

    // Map of allowed type slugs → model classes
    private array $typeMap = [
        'members'            => Member::class,
        'ebooks'             => Ebook::class,
        'authors'            => Author::class,
        'categories'         => Category::class,
        'reviews'            => Review::class,
        'subscription-plans' => SubscriptionPlan::class,
    ];

    private array $searchMap = [
        'members'            => ['full_name'],
        'ebooks'             => ['title'],
        'authors'            => ['first_name', 'last_name'],
        'categories'         => ['name'],
        'reviews'            => ['comment'],
        'subscription-plans' => ['name'],
    ];

    public function index(Request $request)
    {
        // 1. First, deduce type. Prefer session if empty, but explicitly allow overriding via request.
        // We will just let HandlesAdminFilters do its thing, where type is a filterable field.
        
        $type = request('type', 'members');
        if (!array_key_exists($type, $this->typeMap)) {
            $type = 'members';
            $request->merge(['type' => 'members']); // force valid
        }

        $query = $this->typeMap[$type]::onlyTrashed();

        // 2. Counts per tab
        $counts = [];
        foreach ($this->typeMap as $key => $model) {
            $counts[$key] = $model::onlyTrashed()->count();
        }

        // 3. Apply Filters
        $searchFields = $this->searchMap[$type] ?? [];
        $query = $this->applyFilters(
            $query,
            $request,
            'filter_archive',
            $searchFields,
            ['type']
        );

        $records = $query->paginate(15)->appends($request->query());

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
