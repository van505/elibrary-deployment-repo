<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

trait HandlesAdminFilters
{
    /**
     * Apply standardized filtering, searching, sorting, and session logic.
     *
     * @param Builder $query Eloquent query builder.
     * @param Request $request Current request.
     * @param string $prefix Session prefix key (e.g. 'filter_ebooks').
     * @param array $searchableFields DB columns to search (supports relation.column).
     * @param array $filterableFields DB columns to match exactly.
     * @param array $dateFields DB columns to filter by date range.
     * @return Builder
     */
    public function applyFilters(Builder $query, Request $request, string $prefix, array $searchableFields = [], array $filterableFields = [], array $dateFields = [])
    {
        // 1. Clear session if requested
        if ($request->has('clear')) {
            $this->clearFilterSession($prefix);
            // After clearing, we want to strip the parameters entirely from Request 
            // so it runs a clean default query. We also redirect normally in controllers.
            // But if called natively, we replace request input with empty array.
            $request->replace([]);
        }

        // 2. Load or Save Session
        $filterKeys = array_merge(['search', 'sort', 'dir'], $filterableFields);
        foreach ($dateFields as $date) {
            $filterKeys[] = $date . '_start';
            $filterKeys[] = $date . '_end';
        }

        // If request has ANY filter keys (even empty ones like ?search=), it's a submission.
        $hasSubmission = false;
        foreach ($filterKeys as $key) {
            if ($request->has($key)) {
                $hasSubmission = true;
                break;
            }
        }

        if ($hasSubmission) {
            $this->saveFilterSession($request, $prefix, $filterKeys);
        } else {
            $request->merge($this->loadFilterSession($prefix));
        }

        // 3. Search Logic
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm, $searchableFields) {
                foreach ($searchableFields as $index => $field) {
                    if (str_contains($field, '.')) {
                        // Relationship search (e.g., 'authors.full_name')
                        [$relation, $relField] = explode('.', $field);
                        $method = $index === 0 ? 'whereHas' : 'orWhereHas';
                        $q->$method($relation, function ($rq) use ($relField, $searchTerm) {
                            $rq->where($relField, 'like', '%' . $searchTerm . '%');
                        });
                    } else {
                        // Current table search
                        if ($index === 0) {
                            $q->where($field, 'like', '%' . $searchTerm . '%');
                        } else {
                            $q->orWhere($field, 'like', '%' . $searchTerm . '%');
                        }
                    }
                }
            });
        }

        // 4. Exact Match Filters
        foreach ($filterableFields as $field) {
            if ($request->filled($field)) {
                $query->where($field, $request->get($field));
            }
        }

        // 5. Date Range Filters (whereBetween)
        foreach ($dateFields as $field) {
            if ($request->filled($field . '_start')) {
                $query->where($field, '>=', $request->get($field . '_start'));
            }
            if ($request->filled($field . '_end')) {
                // Inclusive up to the end of the day
                $endDate = \Carbon\Carbon::parse($request->get($field . '_end'))->endOfDay();
                $query->where($field, '<=', $endDate);
            }
        }

        // 6. Sorting Logic
        $sortColumn = $request->get('sort', 'created_at');
        $sortDirection = $request->get('dir', 'desc');

        // Note: For relationship sorting, logic would be more complex. We stick to base table columns.
        $query->orderBy($sortColumn, $sortDirection);

        return $query;
    }

    protected function saveFilterSession(Request $request, string $prefix, array $keys)
    {
        foreach ($keys as $key) {
            if ($request->has($key)) {
                Session::put("{$prefix}_{$key}", $request->get($key));
            }
        }
    }

    protected function loadFilterSession(string $prefix)
    {
        $sessionData = [];
        foreach (Session::all() as $key => $value) {
            if (str_starts_with($key, $prefix . '_')) {
                $field = substr($key, strlen($prefix) + 1);
                $sessionData[$field] = $value;
            }
        }
        return $sessionData;
    }

    protected function clearFilterSession(string $prefix)
    {
        foreach (Session::all() as $key => $value) {
            if (str_starts_with($key, $prefix . '_')) {
                Session::forget($key);
            }
        }
    }
}
