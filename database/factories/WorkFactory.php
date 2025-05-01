<?php

namespace Database\Factories;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Work>
 */
class WorkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date = $this->faker->dateTimeBetween('-1 month', '+1 week');

        return [
            'reservation_id' => Reservation::factory(),
            'scheduled_for' => $date->format('Y-m-d'),
            'start_time' => $this->faker->optional()->time('H:i'),
            'end_time' => $this->faker->optional()->time('H:i'),
            'is_completed' => $this->faker->boolean(80),
            'is_approved_by_parent' => $this->faker->boolean(70),
            'invoiced_at' => $this->faker->optional(0.5)->dateTimeBetween($date, 'now'),
        ];
    }
}
