<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Availability;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Availabilities\AvailabilityRequest;

class AvailabilityController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create(): Response
    {
        $availabilities = Availability::where('user_id', Auth::id())
            ->orderBy('start_date')
            ->orderBy('start_time')
            ->get();

        $isAvailable = Availability::isAvailable()->pluck('is_available')->first() ?? false;

        $dayOfMounthAvailable = Availability::countAvailableDaysThisMonth(Auth::id()) ?? 0;

        return Inertia::render('settings/Availability', [
            'availabilities' => $availabilities,
            'isAvailable' => $isAvailable,
            'dayOfMounthAvailable' => $dayOfMounthAvailable,
        ]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  AvailabilityRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit(Availability $availability)
    {
        return Inertia::render('availabilities/edit', [
            'availability' => $availability,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AvailabilityRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(AvailabilityRequest $request)
    {
        Availability::create([
            'user_id' => Auth::id(),
            ...$request->validated()
        ]);

        return back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  AvailabilityRequest  $request
     * @param  \App\Models\Availability  $availability
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(AvailabilityRequest $request, Availability $availability)
    {
        $availability->update($request->validated());

        return redirect()->route('availabilities.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Availability  $availability
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Availability $availability)
    {
        $availability->delete();

        return redirect()->route('availabilities.index');
    }
}
