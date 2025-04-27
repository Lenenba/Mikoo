<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\SearchBabysitterController;
use App\Http\Controllers\BabysitterProfileController;
use App\Http\Controllers\Dashboard\DashboardController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

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
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
