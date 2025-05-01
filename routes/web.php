<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\SearchBabysitterController;
use App\Http\Controllers\AcceptReservationController;
use App\Http\Controllers\BabysitterProfileController;
use App\Http\Controllers\CancelReservationController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\WelcomeController;

Route::get('/', WelcomeController::class)
    ->name('home');

// Authenticated User Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::resource('/availabilities', AvailabilityController::class)
        ->except(['show', 'store', 'create', 'index']);

    Route::get('/search', SearchBabysitterController::class)
        ->name('search.babysitter');

    Route::get('/babysitter/show/{user}', [BabysitterProfileController::class, 'show'])
        ->name('babysitter.show');

    Route::get('/reservations', [ReservationController::class, 'index'])
        ->name('reservations.index');

    Route::get('/reservations/create/{user}', [ReservationController::class, 'create'])
        ->name('reservations.create');
    Route::post('/reservations/store', [ReservationController::class, 'store'])
        ->name('reservations.store');

    Route::post('/reservations/{reservationId}/accept', AcceptReservationController::class)
        ->name('reservations.accept');
    Route::post('/reservations/{reservationId}/cancel', CancelReservationController::class)
        ->name('reservations.cancel');
    Route::get('/reservations/{reservationId}/show', [ReservationController::class, 'show'])
        ->name('reservations.show');

    Route::get('/works', [WorkController::class, 'index'])
        ->name('works.index');
    Route::put('/works/{workId}/update', [WorkController::class, 'update'])
        ->name('works.update');
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
