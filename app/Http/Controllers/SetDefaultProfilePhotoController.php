<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;


class SetDefaultProfilePhotoController extends Controller
{
    public function __invoke(int $photoId)
    {
        $photo = Auth::user()->profile->photos()->findOrFail($photoId);

        if ($photo->is_profile_picture) {
            return redirect()->back()->with('info', 'This photo is already set as your profile picture!');
        }

        // Remove the current profile picture
        $currentProfilePhoto = Auth::user()->profile->photos()->where('is_profile_picture', true)->first();
        if ($currentProfilePhoto) {
            $currentProfilePhoto->update(['is_profile_picture' => false]);
        }

        $photo->update(['is_profile_picture' => true]);

        return redirect()->back()->with('success', 'Profile picture updated successfully!');
    }
}
