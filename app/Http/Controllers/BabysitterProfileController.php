<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Reservation;
use App\Services\ReviewService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\BabysitterProfileRequest;
use Illuminate\Support\Collection;

class BabysitterProfileController extends Controller
{
    /**
     * Show the babysitter profile.
     *
     * @param  \App\Models\User  $user
     * @return \Inertia\Response
     */
    public function show(User $user, ReviewService $reviewService)
    {
        $babysitter = $user->load([
            'profile',
            'profile.photos',
            'profile.certifications',
        ]);
        $reservations = Reservation::where('babysitter_id', $user->id)
            ->with(['works', 'works.reviews', 'works.reviews.photos'])
            ->get();

        // Flatten all reviews from reservations -> works -> reviews
        /** @var Collection $reviews */
        $reviews = $reservations
            ->flatMap->works
            ->flatMap->reviews;

        // Calculate metrics using ReviewService
        $sorted      = $reviewService->sortReviews($reviews, 'newest');
        $average     = $reviewService->averageRating($reviews);
        $total       = $reviewService->totalReviews($reviews);
        $counts      = $reviewService->starCounts($reviews);
        $percentages = $reviewService->starPercentages($reviews);

        // Render Inertia page with the prepared data
        return Inertia::render('babysitter/Index', [
            'babysitter'      => $babysitter,
            'reviews'         => $sorted,
            'averageRating'   => $average,
            'totalReviews'    => $total,
            'starCounts'      => $counts,
            'starPercentages' => $percentages,
        ]);
    }

    /**
     * Show the form for creating a new babysitter profile.
     *
     * @return \Inertia\Response
     */
    public function edit()
    {
        return Inertia::render('settings/ProfileDetails', [
            'profile' => Auth::user()->profile,
            'role'  => Auth::user()->role->name,
        ]);
    }

    /**
     * Update the babysitter profile in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(BabysitterProfileRequest $request)
    {
        // Validate & get only the filled data
        $data = $request->validated();

        // Get the authenticated user
        $user = Auth::user();
        // Get the user's profile
        $profile = $user->profile;
        // Attempt to update: update() ne lancera le save() que s’il y a des attributs modifiés
        $updated = $profile->update($data);

        if ($updated) {
            return redirect()
                ->back()
                ->with('success', 'Profile details updated!');
        }

        // Aucune donnée n’a changé
        return redirect()
            ->back()
            ->with('info', 'No changes detected.');
    }
}
