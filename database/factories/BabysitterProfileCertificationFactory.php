<?php

namespace Database\Factories;

use App\Models\BabysitterProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

class BabysitterProfileCertificationFactory extends Factory
{
    protected $model = \App\Models\BabysitterProfileCertification::class;

    public function definition()
    {
        return [
            'babysitter_profile_id' => BabysitterProfile::factory(),
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
        ];
    }
}
