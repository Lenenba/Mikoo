<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Reservation;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReservationPolicy
{
    use HandlesAuthorization;
    /**
     * Determine whether the user can accept the reservation.
     *
     * @param  \App\Models\User         $user
     * @param  \App\Models\Reservation  $reservation
     * @return bool
     */
    public function accept(User $user, Reservation $reservation): bool
    {
        // Allow super-admins to accept any reservation
        if ($user->role->name === env('SUPER_ADMIN_ROLE')) {
            return true;
        }

        // Only the assigned babysitter can accept their own reservations
        return $user->id === $reservation->babysitter_id;
    }
}
