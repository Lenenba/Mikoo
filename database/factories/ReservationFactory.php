<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ReservationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Reservation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Determine if this reservation recurs (20% chance)
        $isRecurring = $this->faker->boolean(20);

        // Base date for recurrence start
        $recurrenceStart = Carbon::today()->addDays($this->faker->numberBetween(-14, 14));

        // If recurring, set a random end date 1 to 4 weeks after start
        $recurrenceEnd = $isRecurring
            ? (clone $recurrenceStart)->addWeeks($this->faker->numberBetween(1, 4))
            : null;

        // Generate a simple weekly recurrence rule if needed
        $recurrenceRule = null;
        if ($isRecurring) {
            $days = $this->faker->randomElements(
                ['MO', 'TU', 'WE', 'TH', 'FR', 'SA', 'SU'],
                $this->faker->numberBetween(1, 3)
            );
            $recurrenceRule = sprintf(
                'FREQ=WEEKLY;INTERVAL=%d;BYDAY=%s',
                $this->faker->numberBetween(1, 2),
                implode(',', $days)
            );
        }

        return [
            // Reservation made by this user
            'user_id' => User::factory(),

            // Babysitter being booked (ensured distinct from user_id)
            'babysitter_id' => function (array $attributes) {
                // Create a babysitter user and ensure it's not the same as user_id
                $babysitter = User::factory()->create();
                while ($babysitter->id === $attributes['user_id']) {
                    $babysitter = User::factory()->create();
                }
                return $babysitter->id;
            },

            'status'               => $this->faker->randomElement(['pending', 'confirmed', 'canceled']),
            'notes'                => $this->faker->optional()->sentence(),
            'is_recurring'         => $isRecurring,
            'recurrence_rule'      => $recurrenceRule,
            'recurrence_start_date' => $recurrenceStart->toDateString(),
            'recurrence_end_date'  => $recurrenceEnd?->toDateString(),
        ];
    }
}
