<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Member;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ── Public ───────────────────────────────────────────────────────────────────

Route::get('/', fn () => view('welcome'));

// ── Role-based dashboard redirect ────────────────────────────────────────────

Route::middleware('auth')->get('/dashboard', function () {
    return auth()->user()->role === 'admin'
        ? redirect()->route('admin.dashboard')
        : redirect()->route('member.dashboard');
})->name('dashboard');

// ── Profile (Breeze) ─────────────────────────────────────────────────────────

Route::middleware('auth')->group(function () {
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ── Admin ─────────────────────────────────────────────────────────────────────

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::resource('users',      Admin\UserController::class)->except(['create', 'store']);
        Route::resource('authors',    Admin\AuthorController::class);
        Route::resource('categories', Admin\CategoryController::class);
        Route::resource('ebooks',     Admin\EbookController::class);

        Route::resource('members', Admin\MemberController::class)->except(['create', 'store']);

        Route::resource('subscription-plans', Admin\SubscriptionPlanController::class)->except(['show']);
        Route::resource('subscriptions',      Admin\SubscriptionController::class)->only(['index', 'show', 'update']);
        Route::resource('transactions',       Admin\TransactionController::class)->only(['index', 'show']);

        Route::resource('reviews', Admin\ReviewController::class)->only(['index', 'update', 'destroy']);

        Route::get('settings', [Admin\SettingController::class, 'index'])->name('settings.index');
        Route::put('settings', [Admin\SettingController::class, 'update'])->name('settings.update');

        Route::get('activity-logs', [Admin\ActivityLogController::class, 'index'])->name('activity-logs.index');

        // Admin Profile
        Route::get ('profile',        [Admin\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put ('profile/update', [Admin\ProfileController::class, 'update'])->name('profile.update');
    });

// ── Member ────────────────────────────────────────────────────────────────────

Route::middleware(['auth', 'role:member'])
    ->prefix('member')
    ->name('member.')
    ->group(function () {

        Route::get('dashboard', [Member\DashboardController::class, 'index'])->name('dashboard');

        Route::resource('ebooks', Member\EbookController::class)->only(['index', 'show']);

        // Ebook access
        Route::post  ('ebooks/{ebook}/access', [Member\EbookAccessController::class, 'access'])->name('ebooks.access');
        Route::get   ('ebooks/{ebook}/read',   [Member\EbookAccessController::class, 'read'])->name('ebooks.read');
        Route::delete('ebooks/{ebook}/access', [Member\EbookAccessController::class, 'removeAccess'])->name('ebooks.remove-access');

        // Subscriptions
        Route::get ('subscriptions',           [Member\SubscriptionController::class, 'index'])->name('subscriptions.index');
        Route::post('subscriptions/subscribe', [Member\SubscriptionController::class, 'subscribe'])->name('subscriptions.subscribe');

        Route::resource('reviews', Member\ReviewController::class)->only(['index', 'store', 'destroy']);

        // My Reading History
        Route::get('my-ebooks', [Member\MyEbookController::class, 'index'])->name('my-ebooks');

        // Member Profile
        Route::get ('profile',        [Member\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put ('profile/update', [Member\ProfileController::class, 'update'])->name('profile.update');
    });

require __DIR__ . '/auth.php';
