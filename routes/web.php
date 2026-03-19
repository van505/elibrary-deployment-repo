<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Member;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// --------------------------------------------------------------------------
// Public
// --------------------------------------------------------------------------

Route::get('/', function () {
    return view('welcome');
});

// --------------------------------------------------------------------------
// Role-based dashboard redirect
// --------------------------------------------------------------------------

Route::middleware('auth')->get('/dashboard', function () {
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('member.dashboard');
})->name('dashboard');

// --------------------------------------------------------------------------
// Profile (Breeze — keep existing routes)
// --------------------------------------------------------------------------

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --------------------------------------------------------------------------
// Admin routes
// --------------------------------------------------------------------------

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::resource('users', Admin\UserController::class)
            ->except(['create', 'store']);

        Route::resource('authors', Admin\AuthorController::class);
        Route::resource('categories', Admin\CategoryController::class);
        Route::resource('ebooks', Admin\EbookController::class);

        Route::resource('members', Admin\MemberController::class)
            ->except(['create', 'store']);

        Route::resource('borrowings', Admin\BorrowingController::class)
            ->except(['edit', 'update', 'destroy']);
        Route::post('borrowings/{id}/return', [Admin\BorrowingController::class, 'returnBook'])
            ->name('borrowings.return');

        Route::resource('reservations', Admin\ReservationController::class)
            ->only(['index', 'update']);

        Route::resource('payments', Admin\PaymentController::class)
            ->only(['index', 'store', 'show']);

        Route::resource('reviews', Admin\ReviewController::class)
            ->only(['index', 'update', 'destroy']);

        Route::get('settings', [Admin\SettingController::class, 'index'])->name('settings.index');
        Route::put('settings', [Admin\SettingController::class, 'update'])->name('settings.update');

        Route::get('activity-logs', [Admin\ActivityLogController::class, 'index'])->name('activity-logs.index');
    });

// --------------------------------------------------------------------------
// Member routes
// --------------------------------------------------------------------------

Route::middleware(['auth', 'role:member'])
    ->prefix('member')
    ->name('member.')
    ->group(function () {
        Route::get('dashboard', [Member\DashboardController::class, 'index'])->name('dashboard');

        Route::resource('ebooks', Member\EbookController::class)
            ->only(['index', 'show']);

        Route::resource('borrowings', Member\BorrowingController::class)
            ->only(['index', 'store']);

        Route::resource('reservations', Member\ReservationController::class)
            ->only(['index', 'store', 'destroy']);

        Route::resource('reviews', Member\ReviewController::class)
            ->only(['store', 'destroy']);
    });

require __DIR__ . '/auth.php';
