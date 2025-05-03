<?php
// database/factories/InvoiceItemFactory.php

namespace Database\Factories;

use App\Models\InvoiceItem;
use App\Models\Work;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = InvoiceItem::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        // Random hours between 1 and 8.
        $hours     = $this->faker->numberBetween(1, 8);

        // Random unit price between 20.00 and 100.00.
        $unitPrice = $this->faker->randomFloat(2, 20, 100);

        // Compute amount = hours × unit_price.
        $amount    = $hours * $unitPrice;

        return [
            // invoice_id will be set by the InvoiceFactory afterCreating callback.
            'invoice_id'   => null,

            // Associate an existing or new Work record.
            'work_id'      => Work::factory(),

            // Free-form description of the work performed.
            'description'  => $this->faker->sentence(),

            'hours'        => $hours,
            'unit_price'   => $unitPrice,
            'amount'       => $amount,
        ];
    }
}
