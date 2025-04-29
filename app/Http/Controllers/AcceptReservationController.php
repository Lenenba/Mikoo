<?php

namespace App\Http\Controllers;

use App\Models\Reservation;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AcceptReservationController extends Controller
{
    use AuthorizesRequests;
    /**
     * Accept a reservation.
     *
     * @param  int  $reservationId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(int $reservationId)
    {
        //verifier que l'utilisateur est connecté est un babysitter et s'il a le droit d'accepter la réservation
        $this->authorize('accept', Reservation::class);
        // Find the reservation by ID and update its status to 'accepted'
        $reservation = Reservation::findOrFail($reservationId);
        $reservation->update(['status' => 'accepted']);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Reservation accepted successfully!');
    }
}
