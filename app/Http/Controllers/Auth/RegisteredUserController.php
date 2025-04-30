<?php

namespace App\Http\Controllers\Auth;

use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;

class RegisteredUserController extends Controller
{
    /**
     * Show the registration page.
     */
    public function create(): Response
    {
        return Inertia::render('auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'roleName' => 'required|string|in:Parent,Babysitter',
        ]);
        dd('here');

        //Retrieve the Role model based on the submitted name
        $role = Role::where('name', $request->roleName)->firstOrFail();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        //Associate the selected role with the user
        $user->role()->associate($role);
        $user->save();

        $profile = $user->profile()->create();


        $defaultUrl = asset('images/defaultProfil.png');
        $profile->photos()->create([
            'url'                 => $defaultUrl,
            'is_profile_picture'  => true,
        ]);

        $profile->certifications()->create([
            'title' => 'Certification',
            'description' => 'Default description for certification',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return to_route('dashboard');
    }
}
