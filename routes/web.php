<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin & Manager
    Route::prefix('management')->middleware(['role:Admin|Manager'])->group(function () {

        // Booking
        Route::prefix('booking')->group(function () {
            Route::get('/', [BookingController::class, 'index'])->name('booking');
            Route::put('/update-booking-completed/{id}', [BookingController::class, 'updateCompleted'])->name('booking.update.complete');
            Route::put('/update-booking-rejected/{id}', [BookingController::class, 'updateRejected'])->name('booking.update.reject');
        });

        // Slots
        Route::prefix('slots')->group(function () {
            Route::get('/', [BookingController::class, 'index'])->name('slot');
        });
    });

    // Customer
    Route::prefix('customer')->middleware(['role:Customer'])->group(function () {
        // Booking
        Route::prefix('booking')->group(function () {
            Route::get('/slot-booking', [BookingController::class, 'create'])->name('booking.create');
            Route::get('/booking-counts', [BookingController::class, 'bookingCount'])->name('booking.counts');
            Route::post('/store-slot-booking', [BookingController::class, 'store'])->name('booking.store');
            Route::get('/my-bookings', [BookingController::class, 'history'])->name('booking.history');
            Route::put('/cancel-my-booking/{id}', [BookingController::class, 'cancelMyBooking'])->name('booking.cancel');
        });
    });

    // Admin, Manager & Customer
    Route::middleware(['role:Admin|Manager|Customer'])->group(function () {
        Route::prefix('booking')->group(function () {
            Route::get('/booking/{id}', [BookingController::class, 'show'])->name('booking.show');
        });
    });
});

require __DIR__ . '/auth.php';
