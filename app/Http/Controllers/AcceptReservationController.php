<?php

namespace App\Http\Controllers;

use App\Models\Reservation;

use App\Services\WorkSessionGeneratorService;
use App\Notifications\ReservationNotification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AcceptReservationController extends Controller
{
    use AuthorizesRequests;


    protected WorkSessionGeneratorService $workGenerator;

    public function __construct(WorkSessionGeneratorService $workGenerator)
    {
        $this->workGenerator = $workGenerator;
    }

    /**
     * Accept a reservation.
     *
     * @param  int  $reservationId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(int $reservationId)
    {
        $reservation = Reservation::findOrFail($reservationId);

        if ($reservation->status !== 'pending') {
            return redirect()->back()->with('error', 'Reservation is not in a pending state.');
        }
        // Authorize the action using the ReservationPolicy
        $this->authorize('accept', $reservation);

        $reservation->update(['status' => 'confirmed']);

        // English comment: notify the parent via the Notification system
        $reservation->user->notify(
            new ReservationNotification($reservation)
        );

        // Générer les sessions de travail
        $this->workGenerator->generate($reservation);

        // Redirect back with a success message
        return redirect()->route('reservations.show', $reservation->id)->with('success', 'Reservation Confirmed successfully!');
    }
}
