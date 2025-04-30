<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class ProductionUsersSeeder extends Seeder
{
    /**
     * Seed roles and a handful of users for production,
     * each with an empty profile and a default profile photo.
     */
    public function run(): void
    {
        // Create or retrieve the roles
        $adminRole      = Role::firstOrCreate(['name' => 'SuperAdmin']);
        $parentRole     = Role::firstOrCreate(['name' => 'Parent']);
        $babysitterRole = Role::firstOrCreate(['name' => 'Babysitter']);

        // Helper to create a user + profile + default photo
        $createUser = function (string $email, string $name, Role $role) {
            // Create or get the user
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name'     => $name,
                    'password' => 'password',
                    'role_id'  => $role->id,
                ]
            );

            // English comment: Create an empty profile for the user
            $profile = $user->profile()->firstOrCreate([
                'bio' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                'address' => '123 Main St, City, Country',
                'phone' => '123-456-7890',
                'birthdate' => now()->subYears(25)->format('Y-m-d'), // 25 years old
            ]);

            // English comment: Attach a default profile picture
            $defaultUrl = $this->generateFakeProfilePhoto() ?? asset('images/defaultProfil.png');
            $profile->photos()->firstOrCreate(
                ['is_profile_picture' => true],
                ['url' => $defaultUrl]
            );
        };

        // Create Admin user
        $createUser('superadmin@example.com',     'Admin User',      $adminRole);

        // Create two Parent users
        $createUser('parent1@example.com',   'Parent One',      $parentRole);
        $createUser('parent2@example.com',   'Parent Two',      $parentRole);

        // Create two Babysitter users
        $createUser('babysitter1@example.com', 'Babysitter One', $babysitterRole);
        $createUser('babysitter2@example.com', 'Babysitter Two', $babysitterRole);
    }
    /**
     * Generate a random profile photo URL using the Unsplash API.
     *
     * @return string|null
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
}
