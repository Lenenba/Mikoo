<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Availability>
 */
class AvailabilityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('+1 days', '+30 days');
        $endDate = $this->faker->boolean(50)
            ? $startDate
            : $this->faker->dateTimeBetween($startDate, '+30 days');

        $startTime = $this->faker->time('H:i');
        $endTime = $this->faker->time('H:i', '23:59');

        if ($startTime >= $endTime) {
            $endTime = date('H:i', strtotime($startTime . '+1 hour'));
        }

        return [
            'user_id'      => \App\Models\User::factory(),
            'start_date'   => $startDate->format('Y-m-d'),
            'end_date'     => $endDate->format('Y-m-d'),
            'start_time'   => $startTime,
            'end_time'     => $endTime,
            'is_available' => $this->faker->boolean(90),
            'note'         => $this->faker->sentence(),
        ];
    }
}
