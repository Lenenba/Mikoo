<?php

namespace App\Services;

use RRule\RRule;
use Carbon\Carbon;
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


        // 2. Calcule la durée de la session en heures
        $startTime     = Carbon::parse($reservation->start_time);
        $endTime       = Carbon::parse($reservation->end_time);
        $durationHours = $startTime->diffInHours($endTime);
        foreach ($dates as $date) {
            $work  = Work::Create([
                'reservation_id' => $reservation->id,
                'scheduled_for' => is_string($date) ? $date : $date->format('Y-m-d'),
                'start_time' =>  $reservation->start_time,
                'end_time' => $reservation->end_time,
            ]);

            $work->price = $reservation->babysitter->profile->price_per_hour * $durationHours;
            $work->save();
        }
    }
}
