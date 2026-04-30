<?php

namespace App\Providers;

use App\Models\MemberNotification;
use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

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

        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinutes(15, 5)
                ->by($request->email . '|' . $request->ip())
                ->response(function() {
                    return back()->withErrors([
                        'email' => 'Too many login attempts. Please try again in 15 minutes.'
                    ]);
                });
        });

        Password::defaults(function () {
            // Using basic rules rather than ->uncompromised() for local dev stability
            return Password::min(8)
                ->mixedCase()
                ->numbers()
                ->symbols();
        });

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
            
            // Inject active announcements for all users of the member layout
            $activeAnnouncements = \App\Models\Announcement::active()->get();
            $view->with('globalActiveAnnouncements', $activeAnnouncements);
        });

        // ── Admin Notifications ViewComposer ──────────────────────────────────
        View::composer('layouts.admin', function ($view) {
            $adminUnreadCount = \App\Models\AdminNotification::where('is_read', false)->count();
            $adminRecentNotifications = \App\Models\AdminNotification::latest()->limit(5)->get();
            $view->with('adminUnreadCount', $adminUnreadCount)
                 ->with('adminRecentNotifications', $adminRecentNotifications);
        });
    }
}
