<?php

use App\Http\Controllers\Owner\BookingController;
use App\Http\Controllers\Owner\DashboardController;
use App\Http\Controllers\Owner\PropertyController;
use App\Http\Controllers\Owner\RoomController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
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
        Route::resource('properties', PropertyController::class);

        // Rooms (nested under property)
        Route::prefix('properties/{property}')->name('properties.')->group(function () {
            Route::resource('rooms', RoomController::class);
        });

        // Bookings
        Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
        Route::patch('/bookings/{booking}/confirm', [BookingController::class, 'confirm'])->name('bookings.confirm');
        Route::patch('/bookings/{booking}/check-in', [BookingController::class, 'checkIn'])->name('bookings.check-in');
        Route::patch('/bookings/{booking}/check-out', [BookingController::class, 'checkOut'])->name('bookings.check-out');
    });

require __DIR__.'/auth.php';
