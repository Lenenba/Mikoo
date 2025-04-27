<?php

namespace Database\Factories;

use App\Models\BabysitterProfile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BabysitterProfilePhoto>
 */
class BabysitterProfilePhotoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'babysitter_profile_id' => BabysitterProfile::factory(),
            'url'                   => $this->generateFakeCompanyLogo2(),
            'is_profile_picture'    => $this->faker->boolean(),
        ];
    }


    /**
     * Generate a fake profile photo URL for a babysitter.
     */
    private function generateFakeProfilePhoto(): ?string
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Client-ID ' . env('UNSPLASH_ACCESS_KEY'),
            ])->get('https://api.unsplash.com/photos/random', [
                'query' => 'person',
                'orientation' => 'squarish',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['urls']['regular'] ?? null;
            }

            Log::error('Unsplash API error: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Unsplash API exception: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Generate a fake company logo URL using One API Pro Placeholder Image Generator.
     *
     * @return string
     */
    private function generateFakeCompanyLogo2(): string
    {
        $bgColor = ltrim($this->faker->hexColor(), '#'); // Couleur d'arrière-plan aléatoire sans le '#'
        $text = strtoupper(substr($this->faker->company(), 0, 3)); // Premières 3 lettres du nom de l'entreprise
        $width = 150;
        $height = 150;

        return "https://api.oneapipro.com/images/placeholder?text={$text}&width={$width}&height={$height}&color={$bgColor}";
    }
}
