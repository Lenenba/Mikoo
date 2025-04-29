<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    public function definition(): array
    {
        // Générer des dates réalistes
        $startDate = Carbon::instance($this->faker->dateTimeBetween('first day of January', 'last day of December'));
        $endDate = (clone $startDate)->addMonths(rand(1, 3));

        // Générer les jours de récurrence aléatoires
        $days = collect(['MO', 'TU', 'WE', 'TH', 'FR', 'SA', 'SU'])
            ->random(rand(1, 3))
            ->implode(',');

        // Créer la recurrence_rule au format RFC5545
        $recurrenceRule = sprintf(
            "DTSTART:%s\nRRULE:FREQ=WEEKLY;INTERVAL=%d;UNTIL=%s;BYDAY=%s",
            $startDate->format('Ymd'),
            rand(1, 5),
            $endDate->format('Ymd'),
            $days
        );

        return [
            'user_id' => User::factory(),
            'babysitter_id' => User::factory(),
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'canceled']),
            'notes' => $this->faker->optional()->sentence(),
            'is_recurring' => true,
            'recurrence_freq' => 'weekly',
            'recurrence_interval' => rand(1, 5),
            'recurrence_start_date' => $startDate->toDateString(),
            'recurrence_end_date' => $endDate->toDateString(),
            'recurrence_rule' => $recurrenceRule,
        ];
    }
}
