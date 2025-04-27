<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ReservationController extends Controller
{
    /**
     * Display a listing of the reservations.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        // Fetch reservations with related user, ordered by creation date
        $reservations = Reservation::ByUser(Auth::id())
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return Inertia::render('reservations/index', [
            'reservations' => $reservations,
        ]);
    }

    /**
     * Show the form for creating a new reservation.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        // Fetch users for selection dropdown
        $users = User::select('id', 'name')->get();

        return Inertia::render('Reservations/Create', [
            'users' => $users,
        ]);
    }

    /**
     * Store a newly created reservation in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate incoming request
        $data = $request->validate([
            'user_id'                => ['required', 'exists:users,id'],
            'notes'                  => ['nullable', 'string'],
            'is_recurring'           => ['required', 'boolean'],
            'recurrence_rule'        => ['nullable', 'string'],
            'recurrence_start_date'  => ['required', 'date'],
            'recurrence_end_date'    => ['nullable', 'date', 'after_or_equal:recurrence_start_date'],
        ]);

        // Create reservation
        Reservation::create($data);

        return redirect()
            ->route('reservations.index')
            ->with('success', 'Reservation created successfully.');
    }

    /**
     * Display the specified reservation.
     *
     * @param  Reservation  $reservation
     * @return \Inertia\Response
     */
    public function show(Reservation $reservation)
    {
        // Load related user
        $reservation->load('user');

        return Inertia::render('Reservations/Show', [
            'reservation' => $reservation,
        ]);
    }

    /**
     * Show the form for editing the specified reservation.
     *
     * @param  Reservation  $reservation
     * @return \Inertia\Response
     */
    public function edit(Reservation $reservation)
    {
        // Fetch users for dropdown
        $users = User::select('id', 'name')->get();

        return Inertia::render('Reservations/Edit', [
            'reservation' => $reservation,
            'users'       => $users,
        ]);
    }

    /**
     * Update the specified reservation in storage.
     *
     * @param  Request      $request
     * @param  Reservation  $reservation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Reservation $reservation)
    {
        // Validate incoming request
        $data = $request->validate([
            'user_id'                => ['required', 'exists:users,id'],
            'notes'                  => ['nullable', 'string'],
            'is_recurring'           => ['required', 'boolean'],
            'recurrence_rule'        => ['nullable', 'string'],
            'recurrence_start_date'  => ['required', 'date'],
            'recurrence_end_date'    => ['nullable', 'date', 'after_or_equal:recurrence_start_date'],
        ]);

        // Update reservation
        $reservation->update($data);

        return redirect()
            ->route('reservations.index')
            ->with('success', 'Reservation updated successfully.');
    }

    /**
     * Remove the specified reservation from storage.
     *
     * @param  Reservation  $reservation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Reservation $reservation)
    {
        // Delete reservation
        $reservation->delete();

        return redirect()
            ->route('reservations.index')
            ->with('success', 'Reservation deleted successfully.');
    }

    /**
     * Respond to a reservation by changing its status.
     *
     * @param  Request      $request
     * @param  Reservation  $reservation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function respond(Request $request, Reservation $reservation)
    {
        // Validate status change
        $data = $request->validate([
            'status' => ['required', 'in:pending,confirmed,canceled'],
        ]);

        // Update reservation status
        $reservation->update(['status' => $data['status']]);

        return redirect()
            ->route('reservations.show', $reservation)
            ->with('success', 'Reservation status updated.');
    }
}
