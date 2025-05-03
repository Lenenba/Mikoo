<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DeleteProfilePhotoController extends Controller
{
    public function __invoke(int $photoId)
    {
        $photo = Auth::user()->profile->photos()->findOrFail($photoId);

        if ($photo->is_profile_picture) {
            return redirect()->back()->with('info', 'You cannot delete your profile picture!');
        }

        $photo->delete();

        return redirect()->back()->with('success', 'Profile photo deleted successfully!');
    }
}
