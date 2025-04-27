<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;

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
}
