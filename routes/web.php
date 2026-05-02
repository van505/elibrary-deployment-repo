<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Member;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ── Public ───────────────────────────────────────────────────────────────────

use App\Models\Ebook;
use App\Models\Category;

Route::get('/debug-db', function () {
    try {
        $db = DB::connection()->getDatabaseName();
        $users = DB::table('users')->get();
        return response()->json([
            'connection' => config('database.default'),
            'database' => $db,
            'host' => config('database.connections.mysql.host'),
            'users_count' => count($users),
            'users' => $users
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()]);
    }
});

Route::get('/', function () {
    $categories = Category::orderBy('name')->get();

    // Load up to 3 featured books for the hero section
    $heroBooks = Ebook::whereIn('status', ['active', 'Active', 'published', 'Published'])
        ->where('is_featured', true)
        ->latest()
        ->limit(3)
        ->get();

    // Editor's Choice spotlight
    $spotlightEbook = Ebook::with('authors')
        ->whereIn('status', ['active', 'Active', 'published', 'Published'])
        ->where('is_spotlighted', true)
        ->first();

    // Load all books with category for client-side filtering, sorted by access level
    $featuredBooks = Ebook::with(['authors', 'category'])
        ->whereIn('status', ['active', 'Active', 'published', 'Published'])
        ->orderByRaw("FIELD(access_level, 'free', 'basic', 'premium')")
        ->latest()
        ->get();

    $featuredCollections = \App\Models\Collection::active()->withCount('ebooks')->latest()->take(4)->get();

    return view('welcome', compact('categories', 'featuredBooks', 'heroBooks', 'spotlightEbook', 'featuredCollections'));
});

// Search Autocomplete API (Public)
Route::get('/search/suggestions', [App\Http\Controllers\SearchController::class, 'suggestions'])
    ->middleware('throttle:60,1')
    ->name('search.suggestions');

// ── Role-based dashboard redirect ────────────────────────────────────────────

Route::get ('2fa',        [App\Http\Controllers\Auth\TwoFactorController::class, 'challenge'])->name('2fa.challenge')->middleware('auth');
Route::post('2fa',        [App\Http\Controllers\Auth\TwoFactorController::class, 'verify'])->name('2fa.verify')->middleware('auth');
Route::post('2fa/resend', [App\Http\Controllers\Auth\TwoFactorController::class, 'resend'])->name('2fa.resend')->middleware('auth');

Route::middleware(['auth', '2fa'])->get('/dashboard', function () {
    return auth()->user()->role === 'admin'
        ? redirect()->route('admin.dashboard')
        : redirect()->route('member.dashboard');
})->name('dashboard');



// ── Profile (Breeze) ─────────────────────────────────────────────────────────

Route::middleware(['auth', '2fa'])->group(function () {
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // 2FA Enable/Disable (email OTP mode)
    Route::post  ('/2fa/enable',  [App\Http\Controllers\Auth\TwoFactorSetupController::class, 'enable'])->name('2fa.enable');
    Route::delete('/2fa/disable', [App\Http\Controllers\Auth\TwoFactorSetupController::class, 'disable'])->name('2fa.disable');
});

// ── Admin ─────────────────────────────────────────────────────────────────────

Route::middleware(['auth', '2fa', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get ('dashboard',         [Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::post('dashboard/widgets', [Admin\DashboardController::class, 'saveWidgets'])->name('dashboard.widgets');

        Route::resource('users',      Admin\UserController::class)->except(['create', 'store']);
        Route::resource('authors',    Admin\AuthorController::class);
        Route::resource('categories', Admin\CategoryController::class);
        Route::resource('announcements', Admin\AnnouncementController::class)->except(['show']);
        
        // Collections
        Route::resource('collections', Admin\CollectionController::class);
        Route::post('collections/{collection}/ebooks', [Admin\CollectionController::class, 'addEbook'])->name('collections.add-ebook');
        Route::delete('collections/{collection}/ebooks/{ebook}', [Admin\CollectionController::class, 'removeEbook'])->name('collections.remove-ebook');
        Route::patch('collections/{collection}/ebooks/{ebook}/move/{direction}', [Admin\CollectionController::class, 'moveEbook'])->name('collections.move-ebook');
        
        Route::get('ebooks/{ebook}/stream', [Admin\EbookController::class, 'stream'])->name('ebooks.stream');
        Route::post('ebooks/{ebook}/spotlight', [Admin\EbookController::class, 'spotlight'])->name('ebooks.spotlight');
        Route::post('ebooks/bulk-action', [Admin\EbookController::class, 'bulkAction'])->name('ebooks.bulk-action');
        Route::get('api/ebooks/{ebook}', [Admin\EbookController::class, 'apiShow'])->name('api.ebooks.show');
        Route::resource('ebooks',     Admin\EbookController::class);

        Route::resource('members', Admin\MemberController::class)->except(['create', 'store']);

        Route::resource('subscription-plans', Admin\SubscriptionPlanController::class)->except(['show']);
        Route::resource('subscriptions',      Admin\SubscriptionController::class)->only(['index', 'show', 'update']);
        Route::resource('transactions',       Admin\TransactionController::class)->only(['index', 'show']);

        Route::resource('reviews', Admin\ReviewController::class)->only(['index', 'update', 'destroy']);
        Route::patch('reviews/{review}/approve', [Admin\ReviewController::class, 'approve'])->name('reviews.approve');
        Route::post('reviews/bulk', [Admin\ReviewController::class, 'bulkAction'])->name('reviews.bulk');

        Route::get('reports', [Admin\ReportController::class, 'index'])->name('reports.index');

        // Admin Notifications
        Route::get('notifications', [Admin\NotificationController::class, 'index'])->name('notifications.index');
        Route::post('notifications/mark-all-read', [Admin\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
        Route::post('notifications/{notification}/read', [Admin\NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
        Route::delete('notifications/{notification}', [Admin\NotificationController::class, 'destroy'])->name('notifications.destroy');

        Route::post('members/{id}/toggle-status', [Admin\MemberController::class, 'toggleStatus'])->name('members.toggle-status');

        Route::get('settings', [Admin\SettingController::class, 'index'])->name('settings.index');
        Route::put('settings', [Admin\SettingController::class, 'update'])->name('settings.update');

        Route::get('activity-logs', [Admin\ActivityLogController::class, 'index'])->name('activity-logs.index');
        Route::delete('activity-logs/clear', [Admin\ActivityLogController::class, 'clear'])->name('activity-logs.clear');

        // Archive (soft delete management)
        Route::get   ('archive',                          [Admin\ArchiveController::class, 'index']      )->name('archive.index');
        Route::patch ('archive/{type}/{id}/restore',      [Admin\ArchiveController::class, 'restore']    )->name('archive.restore');
        Route::delete('archive/{type}/{id}/force-delete', [Admin\ArchiveController::class, 'forceDelete'])->name('archive.force-delete');

        // Admin Profile
        Route::get ('profile',        [Admin\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put ('profile/update', [Admin\ProfileController::class, 'update'])->name('profile.update');
    });

// ── Member ────────────────────────────────────────────────────────────────────

// Onboarding routes: inside auth + role guards but WITHOUT the onboarding middleware
Route::middleware(['auth', '2fa', 'verified', 'role:member'])
    ->prefix('member')
    ->name('member.')
    ->group(function () {
        Route::get ('onboarding',       [Member\OnboardingController::class, 'show'])->name('onboarding.show');
        Route::post('onboarding/step1', [Member\OnboardingController::class, 'saveStep1'])->name('onboarding.step1');
        Route::post('onboarding/step2', [Member\OnboardingController::class, 'saveStep2'])->name('onboarding.step2');
        Route::post('onboarding/step3', [Member\OnboardingController::class, 'saveStep3'])->name('onboarding.step3');
        Route::post('onboarding/skip',  [Member\OnboardingController::class, 'skip'])->name('onboarding.skip');
    });

// All other member routes: includes onboarding middleware to enforce the flow
Route::middleware(['auth', '2fa', 'verified', 'role:member', 'onboarding'])
    ->prefix('member')
    ->name('member.')
    ->group(function () {

        Route::get('dashboard', [Member\DashboardController::class, 'index'])->name('dashboard');

        Route::resource('ebooks', Member\EbookController::class)->only(['index', 'show']);
        
        // Collections
        Route::get('collections', [Member\CollectionController::class, 'index'])->name('collections.index');
        Route::get('collections/{collection:slug}', [Member\CollectionController::class, 'show'])->name('collections.show');

        // Ebook access
        Route::post  ('ebooks/{ebook}/access', [Member\EbookAccessController::class, 'access'])->name('ebooks.access');
        Route::get   ('ebooks/{ebook}/read',   [Member\EbookAccessController::class, 'read'])->name('ebooks.read');
        Route::get   ('ebooks/{ebook}/stream', [Member\EbookAccessController::class, 'stream'])->name('ebooks.stream');
        Route::get   ('ebooks/{ebook}/preview-stream', [Member\EbookAccessController::class, 'previewStream'])
            ->middleware(['signed', 'throttle:20,1'])
            ->name('ebooks.preview-stream');
        Route::delete('ebooks/{ebook}/access', [Member\EbookAccessController::class, 'removeAccess'])->name('ebooks.remove-access');

        // Subscriptions
        Route::get ('subscriptions',           [Member\SubscriptionController::class, 'index'])->name('subscriptions.index');
        Route::post('subscriptions/subscribe', [Member\SubscriptionController::class, 'subscribe'])->name('subscriptions.subscribe');

        Route::resource('reviews', Member\ReviewController::class)->only(['index', 'store', 'destroy', 'update']);

        // My Reading History
        Route::get('my-ebooks', [Member\MyEbookController::class, 'index'])->name('my-ebooks');
        Route::delete('my-ebooks/clear', [Member\MyEbookController::class, 'clear'])->name('my-ebooks.clear');
        Route::delete('my-ebooks/{id}', [Member\MyEbookController::class, 'destroy'])->name('my-ebooks.destroy');

        // Member Profile
        Route::get ('profile',        [Member\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put ('profile/update', [Member\ProfileController::class, 'update'])->name('profile.update');

        // Archive
        Route::get ('archive', [Member\ArchiveController::class, 'index'])->name('archive.index');

        // Help & Support
        Route::get ('help', [Member\HelpController::class, 'index'])->name('help');

        // Bookmarks
        Route::get ('bookmarks',              [Member\BookmarkController::class, 'index'])->name('bookmarks.index');
        Route::post('bookmarks/{ebook}/toggle',[Member\BookmarkController::class, 'toggle'])->name('bookmarks.toggle');

        // Wishlists
        Route::get ('wishlist',               [Member\WishlistController::class, 'index'])->name('wishlist.index');
        Route::post('wishlist/{ebook}/toggle', [Member\WishlistController::class, 'toggle'])->name('wishlist.toggle');

        // Notifications
        Route::post('notifications/{id}/read', [Member\NotificationController::class, 'markRead'])->name('notifications.read');
        Route::post('notifications/read-all',  [Member\NotificationController::class, 'markAllRead'])->name('notifications.read-all');
    });

require __DIR__ . '/auth.php';
