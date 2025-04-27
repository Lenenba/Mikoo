<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BabysitterProfilePhoto;
use Illuminate\Support\Facades\Storage;

class BabysitterProfilePhotoController extends Controller
{
    public function create()
    {
        $user = User::with('profile.photos')
            ->find(Auth::id());
        $photos = $user?->profile?->photos ?? [];
        return Inertia::render('settings/profilePhoto/Create', [
            'photos' => $photos,
        ]);
    }

    public function store(Request $request)
    {
        $user = User::with('profile.photos')
            ->find(Auth::id());
        if ($request->hasFile('images')) {
            $request->validate([
                'images.*' => 'mimes:jpg,png,jpeg,webp|max:5000'
            ], [
                'images.*.mimes' => 'The file should be in one of the formats: jpg, png, jpeg, webp'
            ]);
            foreach ($request->file('images') as $file) {
                $path = $file->store('images', 'public');

                $user->profile->photos()->save(new BabysitterProfilePhoto([
                    'url' => $path
                ]));
            }
        }

        return redirect()->back()->with('success', 'Images uploaded!');
    }

    public function destroy(BabysitterProfilePhoto $image)
    {
        Storage::disk('public')->delete($image->url);
        $image->delete();

        return redirect()->back()->with('success', 'Image was deleted!');
    }
}
