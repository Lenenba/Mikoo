<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;


class WelcomeController extends Controller
{
    /**
     * Show the application welcome page.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        $babysitters = User::where('role_id', 3)
            ->with(['profile', 'profile.photos', 'profile.certifications'])
            ->get();
        return Inertia::render('Welcome', [
            'babysitters' => $babysitters,
        ]);
    }
}
