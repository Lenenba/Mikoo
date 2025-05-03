<?php
// database/factories/InvoiceFactory.php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\User;
use App\Models\InvoiceItem;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class InvoiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Invoice::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        // Pick a random start date within the last month.
        $periodStart = $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d');

        // Ensure period_end is on or after period_start.
        $periodEnd = Carbon::parse($periodStart)
            ->addDays($this->faker->numberBetween(1, 30))
            ->format('Y-m-d');

        return [
            // Associate an existing or new babysitter user.
            'babysitter_id' => User::factory(),

            'period_start'  => $periodStart,
            'period_end'    => $periodEnd,

            // Random status among draft, sent or paid.
            'status'        => $this->faker->randomElement(['draft', 'sent', 'paid']),

            // We'll calculate total_amount after items are created.
            'total_amount'  => 0,
        ];
    }

    /**
     * Configure the factory to create related items and compute total.
     */
    public function configure()
    {
        return $this->afterCreating(function (Invoice $invoice) {
            // Create between 1 and 5 invoice items tied to this invoice.
            $items = InvoiceItem::factory()
                ->count($this->faker->numberBetween(1, 5))
                ->create(['invoice_id' => $invoice->id]);

            // Sum up the amounts of all items.
            $total = $items->sum('amount');

            // Update the invoice with the real total.
            $invoice->update(['total_amount' => $total]);
        });
    }
}
