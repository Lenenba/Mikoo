<?php

namespace App\Services;

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class ReservationToFullCalendarService
{
    /**
     * Transform reservations into FullCalendar-compatible events array.
     *
     * @param Collection|Reservation[] $reservations
     * @return array
     */
    public function transform(Collection $reservations): array
    {
        return $reservations->map(function (Reservation $reservation) {
            if ($reservation->is_recurring && $reservation->recurrence_rule) {
                return [
                    'id' => $reservation->id,
                    'title' => User::find($reservation->user_id)->name,
                    'rrule' => str_replace('\n', "\n", $reservation->recurrence_rule), // corriger \n stocké en vrai retour ligne
                ];
            }

            // Event simple (non récurrent)
            return [
                'id' => $reservation->id,
                'title' => 'Reservation',
                'start' => $this->formatDateTime($reservation->recurrence_start_date),
                'end' => $this->formatDateTime($reservation->recurrence_end_date),
            ];
        })->filter()->values()->toArray();
    }

    /**
     * Format date to ISO 8601 FullCalendar standard.
     *
     * @param string|\DateTimeInterface|null $date
     * @return string|null
     */
    private function formatDateTime($date): ?string
    {
        if (!$date) {
            return null;
        }

        return Carbon::parse($date)->toIso8601String();
    }
}
