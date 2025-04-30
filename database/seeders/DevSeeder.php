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

class DevSeeder extends Seeder
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
                ->count(3)
                ->create([
                    'role_id' => $role->id,
                ]);
            // Create 10 availabilities for clients
            Availability::factory()
                ->count(2)
                ->recycle($clients)
                ->create();


            foreach ($clients as $client) {
                BabysitterProfile::factory()->for($client)->create();
            }
        }

        $superAdminRole = Role::where('name', 'SuperAdmin')->firstOrFail();
        // Create a super admin user
        $superadmin = User::factory()->create([
            'name'  => 'Super Admin',
            'email' => 'superadmin@example.com',
            'role_id' => $superAdminRole->id, // Assuming 1 is the ID for SuperAdmin role
        ]);
        $adminProfil = BabysitterProfile::factory()->for($superadmin)->create();
        BabysitterProfilePhoto::factory()
            ->for($adminProfil)
            ->create([
                'is_profile_picture' => true,
            ]);
        // Create 5 availabilities for superadmin as babysitter
        Availability::factory()
            ->count(5)
            ->for($superadmin, 'user')
            ->create();

        $babysitters = User::FindRole(env('BABYSITTER_ROLE'))
            ->MostRecent()
            ->get();

        $babysitters->each(function ($babysitter) {
            // Create two additional photos (is_profile_picture = false)
            BabysitterProfilePhoto::factory()
                ->for($babysitter->profile)
                ->create([
                    'is_profile_picture' => true,
                ]);
            // Create two additional photos (is_profile_picture = false)
            // BabysitterProfilePhoto::factory()
            //     ->count(5)
            //     ->for($babysitter->profile)
            //     ->create([
            //         'is_profile_picture' => false,
            //     ]);
            // BabysitterProfileCertification::factory()->count(2)->for($babysitter->profile)->create();

            $parent = User::where('role_id', 4)->get();

            $parent->each(function ($parent) use ($babysitter) {
                // Create 5 reservations for each babysitter
                Reservation::factory()
                    ->count(2)
                    ->create([
                        'user_id' => $parent->id,
                        'babysitter_id' => $babysitter->id,
                    ]);
            });
        });
    }
}
