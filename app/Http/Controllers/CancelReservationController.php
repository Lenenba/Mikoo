<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Notifications\ReservationNotification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CancelReservationController extends Controller
{
    use AuthorizesRequests;

    /**
     * Cancel a reservation.
     *
     * @param  int  $reservationId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(int $reservationId)
    {
        $reservation = Reservation::findOrFail($reservationId);

        if ($reservation->status === 'canceled') {
            return redirect()->back()->with('error', 'This Reservation can not be cancelled.');
        }

        // Authorize the action using the ReservationPolicycanceled
        $this->authorize('cancel', $reservation);

        $reservation->update(['status' => 'canceled']);

        // English comment: notify the parent via the Notification system
        $reservation->user->notify(
            new ReservationNotification($reservation)
        );

        // Redirect back with a success message
        return redirect()->route('reservations.show', $reservation->id)->with('success', 'Reservation canceled successfully!');
    }
}
