<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\PropertyController as AdminPropertyController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\Owner\BookingController as OwnerBookingController;
use App\Http\Controllers\Owner\DashboardController;
use App\Http\Controllers\Owner\PropertyController as OwnerPropertyController;
use App\Http\Controllers\Owner\RoomController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $properties = \App\Models\Property::active()
        ->withCount('rooms')
        ->with('rooms')
        ->latest()
        ->take(6)
        ->get();
    return view('welcome', compact('properties'));
});

Route::get('/dashboard', function () {
    $role = auth()->user()->role;
    return match($role) {
        'owner', 'admin' => redirect()->route('owner.dashboard'),
        default          => redirect('/'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

// =====================
// Public Routes
// =====================
Route::get('/properties', [PropertyController::class, 'index'])->name('properties.index');
Route::get('/properties/{property}', [PropertyController::class, 'show'])->name('properties.show');

// =====================
// Booking Routes (Guest)
// =====================
Route::middleware(['auth'])->group(function () {
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
});

// =====================
// Owner Routes
// =====================
Route::middleware(['auth', 'verified', 'role:owner,admin'])
    ->prefix('owner')
    ->name('owner.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Properties
        Route::resource('properties', OwnerPropertyController::class);

        // Rooms (nested under property)
        Route::prefix('properties/{property}')->name('properties.')->group(function () {
            Route::resource('rooms', RoomController::class);
        });

        // Bookings
        Route::get('/bookings', [OwnerBookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/{booking}', [OwnerBookingController::class, 'show'])->name('bookings.show');
        Route::patch('/bookings/{booking}/confirm', [OwnerBookingController::class, 'confirm'])->name('bookings.confirm');
        Route::patch('/bookings/{booking}/check-in', [OwnerBookingController::class, 'checkIn'])->name('bookings.check-in');
        Route::patch('/bookings/{booking}/check-out', [OwnerBookingController::class, 'checkOut'])->name('bookings.check-out');
    });

    // =====================
// Admin Routes
// =====================
Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Users
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
        Route::patch('/users/{user}/role', [AdminUserController::class, 'updateRole'])->name('users.role');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

        // Properties
        Route::get('/properties', [AdminPropertyController::class, 'index'])->name('properties.index');
        Route::get('/properties/{property}', [AdminPropertyController::class, 'show'])->name('properties.show');
        Route::patch('/properties/{property}/approve', [AdminPropertyController::class, 'approve'])->name('properties.approve');
        Route::patch('/properties/{property}/reject', [AdminPropertyController::class, 'reject'])->name('properties.reject');
        Route::delete('/properties/{property}', [AdminPropertyController::class, 'destroy'])->name('properties.destroy');

        // Bookings
        Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
    });
require __DIR__.'/auth.php';