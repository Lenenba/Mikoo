<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\BabysitterProfileController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\BabysitterProfilePhotoController;
use App\Http\Controllers\DeleteProfilePhotoController;
use App\Http\Controllers\SetDefaultProfilePhotoController;

Route::middleware('auth')->group(function () {
    Route::redirect('settings', '/settings/profile');

    Route::get('settings/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('settings/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('settings/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('settings/password', [PasswordController::class, 'edit'])->name('password.edit');
    Route::put('settings/password', [PasswordController::class, 'update'])->name('password.update');

    Route::get('settings/availabilities', [AvailabilityController::class, 'create'])->name('availabilities.create');
    Route::post('settings/availabilities/store', [AvailabilityController::class, 'store'])->name('availabilities.store');

    Route::get('settings/photos', [BabysitterProfilePhotoController::class, 'create'])->name('photos.create');
    Route::post('settings/photos/store', [BabysitterProfilePhotoController::class, 'store'])->name('photos.store');

    Route::put('/settings/photos/default/{photoId}', SetDefaultProfilePhotoController::class)
        ->name('photos.setDefault');
    Route::delete('/settings/photos/delete/{photoId}', DeleteProfilePhotoController::class)
        ->name('photos.destroy');

    Route::get('settings/profile/details', [BabysitterProfileController::class, 'edit'])
        ->name('babysitter.profile.details.edit');
    Route::post('settings/profile/details', [BabysitterProfileController::class, 'update'])
        ->name('babysitter.profile.details.update');

    Route::get('settings/appearance', function () {
        return Inertia::render('settings/Appearance');
    })->name('appearance');
});
