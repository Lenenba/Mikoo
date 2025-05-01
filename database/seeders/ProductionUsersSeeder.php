<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Models\Work;
use App\Models\Reservation;
use App\Models\WorkSession;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class ProductionUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Create or retrieve roles
        $adminRole = Role::firstOrCreate(['name' => 'SuperAdmin']);
        $parentRole = Role::firstOrCreate(['name' => 'Parent']);
        $babysitterRole = Role::firstOrCreate(['name' => 'Babysitter']);

        // Helper to create user, profile and photo
        $createUser = function (string $email, string $name, Role $role): User {
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name'     => $name,
                    'password' => 'password',
                    'role_id'  => $role->id,
                ]
            );

            $profile = $user->profile()->firstOrCreate([
                'bio' => 'Lorem ipsum dolor sit amet.',
                'address' => '123 Main St',
                'phone' => '123-456-7890',
                'price_per_hour' => 20.00,
                'experience' => '2 years of experience with children',
                'birthdate' => now()->subYears(25)->format('Y-m-d'),
            ]);

            $photoUrl = $this->generateFakeProfilePhoto() ?? asset('images/defaultProfil.png');
            $profile->photos()->firstOrCreate(
                ['is_profile_picture' => true],
                ['url' => $photoUrl]
            );

            return $user;
        };

        // Admin + parents
        $createUser('superadmin@example.com', 'Admin User', $adminRole);
        $parent1 = $createUser('parent1@example.com', 'Parent One', $parentRole);
        $parent2 = $createUser('parent2@example.com', 'Parent Two', $parentRole);

        // Babysitters
        $babysitters = [
            $createUser('babysitter1@example.com', 'Babysitter One', $babysitterRole),
            $createUser('babysitter2@example.com', 'Babysitter Two', $babysitterRole),
        ];

        foreach ($babysitters as $babysitter) {
            $startDate = Carbon::now()->addDays(1)->startOfDay();
            $endDate = Carbon::now()->addDays(21)->startOfDay();

            $reservation = Reservation::create([
                'user_id' => $parent1->id,
                'babysitter_id' => $babysitter->id,
                'status' => 'confirmed',
                'start_time'      => Carbon::now()->addDays(2)->setHour(18)->setMinute(0)->setSecond(0),
                'end_time'        => Carbon::now()->addDays(2)->setHour(21)->setMinute(0)->setSecond(0),
                'is_recurring' => true,
                'recurrence_freq' => 'weekly',
                'recurrence_start_date' => $startDate->toDateString(),
                'recurrence_end_date' => $endDate->toDateString(),
                'recurrence_rule' => 'FREQ=WEEKLY;BYDAY=MO,WE,FR',
                'notes' => 'Auto-generated reservation with recurrence',
            ]);

            // Optional: create some sessions manually to simulate occurrence
            foreach ([0, 2, 4] as $offset) {
                $day = $startDate->copy()->addDays($offset);
                Work::create([
                    'reservation_id' => $reservation->id,
                    'scheduled_for' => $day->toDateString(),
                    'start_time' => '09:00',
                    'end_time' => '12:00',
                    'is_completed' => true,
                    'is_approved_by_parent' => $offset !== 2, // simulate one unapproved
                ]);
            }
        }
    }

    /**
     * Generate a random profile photo URL using Unsplash API.
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
