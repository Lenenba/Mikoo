<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\BabysitterProfileRequest;

class BabysitterProfileController extends Controller
{
    /**
     * Show the babysitter profile.
     *
     * @param  \App\Models\User  $user
     * @return \Inertia\Response
     */
    public function show(User $user)
    {
        $babySitter = User::where('id', $user->id)
            ->with(['profile', 'profile.photos', 'profile.certifications'])
            ->get();

        return Inertia::render('babysitter/Index', [
            'babySitter' => $babySitter,
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
