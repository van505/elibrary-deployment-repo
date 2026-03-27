<?php

namespace App\Providers;

use App\Models\MemberNotification;
use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useTailwind();
        User::observe(UserObserver::class);

        // ── Member Notifications ViewComposer ─────────────────────────────────
        // Automatically injects unread notification count & recent notifications
        // into ALL views that use the member layout — no controller code needed.
        View::composer('layouts.member', function ($view) {
            if (auth()->check() && auth()->user()->role === 'member') {
                $member = auth()->user()->member;
                if ($member) {
                    $unreadNotificationsCount = MemberNotification::where('member_id', $member->id)
                        ->where('is_read', false)
                        ->count();

                    $recentNotifications = MemberNotification::where('member_id', $member->id)
                        ->latest()
                        ->limit(10)
                        ->get();

                    $view->with('unreadNotificationsCount', $unreadNotificationsCount)
                         ->with('recentNotifications', $recentNotifications);
                }
            }
        });
    }
}
