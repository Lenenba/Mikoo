<?php

namespace App\Services;

use RRule\RRule;
use App\Models\Work;
use App\Models\Reservation;

class WorkSessionGeneratorService
{
    public function generate(Reservation $reservation): void
    {
        $dates = [];

        if ($reservation->recurrence_rule) {
            $rule = new RRule($reservation->recurrence_rule);
            $dates = $rule->getOccurrencesBetween(
                $reservation->recurrence_start_date,
                $reservation->recurrence_end_date
            );
        } else {
            $dates[] = $reservation->recurrence_start_date;
        }

        foreach ($dates as $date) {
            Work::firstOrCreate([
                'reservation_id' => $reservation->id,
                'scheduled_for' => is_string($date) ? $date : $date->format('Y-m-d'),
                'start_time' =>  $reservation->start_time,
                'end_time' => $reservation->end_time,
            ]);
        }
    }
}
