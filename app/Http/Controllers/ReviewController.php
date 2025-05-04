<?php

namespace App\Http\Controllers;

use App\Models\Work;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Store a newly created review in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Work          $work
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Work $work)
    {
        // Validate incoming request data
        $validated = $request->validate([
            'rating'   => 'required|integer|min:1|max:5',
            'headline' => 'nullable|string|max:255',
            'review'   => 'nullable|string',
            'media.*'  => 'sometimes|file|image|max:5120', // max 5MB per file
        ]);

        // Create the review linked to the work
        $review = $work->reviews()->create([
            'rating'   => $validated['rating'],
            'headline' => $validated['headline'] ?? null,
            'review'   => $validated['review'] ?? null,
        ]);

        // Handle file uploads (up to 5 photos)
        if ($request->hasFile('media')) {
            // Loop through each uploaded file
            foreach ($request->file('media') as $file) {
                // Store the file in 'public/reviews' and get its path
                $path = $file->store('reviews', 'public');

                // Create a ReviewPhoto record
                $review->photos()->create([
                    'file_path' => $path,
                ]);
            }
        }

        // Redirect back with success message
        return redirect()
            ->back()
            ->with('success', 'Votre avis a bien été enregistré !');
    }
}
