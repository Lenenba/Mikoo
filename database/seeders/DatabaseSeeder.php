<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Reservation;
use Illuminate\Support\Arr;
use App\Models\Availability;
use Illuminate\Database\Seeder;
use App\Models\BabysitterProfile;
use App\Models\BabysitterProfilePhoto;
use App\Models\BabysitterProfileCertification;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roleNames = [
            'SuperAdmin',
            // 'Admin',
            'Guest',
            'Babysitter',
            'Parent',
        ];

        foreach ($roleNames as $roleName) {
            $role = Role::factory()->create([
                'name' => $roleName,
            ]);

            if ($roleName === 'SuperAdmin') {
                continue;
            }

            $clients = User::factory()
                ->count(10)
                ->create([
                    'role_id' => $role->id,
                ]);
            // Create 10 availabilities for clients
            Availability::factory()
                ->count(10)
                ->recycle($clients)
                ->create();
        }

        $superAdminRole = Role::where('name', 'SuperAdmin')->firstOrFail();
        // Create a super admin user
        $superadmin = User::factory()->create([
            'name'  => 'Super Admin',
            'email' => 'superadmin@example.com',
            'role_id' => $superAdminRole->id, // Assuming 1 is the ID for SuperAdmin role
        ]);

        // Create 5 availabilities for superadmin as babysitter
        Availability::factory()
            ->count(5)
            ->for($superadmin, 'user')
            ->create();

        $babysitters = User::FindBabysitter()
            ->MostRecent()
            ->get();

        $babysitters->each(function ($babysitter) {
            $profile = BabysitterProfile::factory()->for($babysitter)->create();
            // Create one profile photo (is_profile = true)
            BabysitterProfilePhoto::factory()
                ->for($profile)
                ->create([
                    'is_profile_picture' => true,
                ]);

            // Create two additional photos (is_profile_picture = false)
            BabysitterProfilePhoto::factory()
                ->count(2)
                ->for($profile)
                ->create([
                    'is_profile_picture' => false,
                ]);
            BabysitterProfileCertification::factory()->count(2)->for($profile)->create();
        });
    }
}
