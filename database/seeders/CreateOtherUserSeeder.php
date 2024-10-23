<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\V1\Hospital\Models\Hospital;
use Modules\V1\User\Models\Role;
use Modules\V1\User\Models\User;
use Spatie\Permission\Models\Permission;

final class CreateOtherUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hospital = Hospital::create([
            'name' => 'HMS Hospital',
            'location' => 'Ahmedabad',
            'phone' => '9876543210',
            'email' => 'updated@hospital.com',
            'description' => 'Updated description.',
        ]);

        $user = User::create([
            'first_name' => 'Doctor',
            'last_name' => 'Admin',
            'email' => 'doctor@gmail.com',
            'password' => bcrypt('123456789'), // Make sure to hash passwords
            'user_type' => 'doctor',
            'email_verified_at' => now(),
        ]);

        // Find the role by its name
        $doctorRole = Role::where('name', 'doctor')->first();

        // Assign the 'admin' role to the user
        if ($doctorRole) {
            $permissions = Permission::where('name', 'like', 'doctor-%')->orWhere('name', 'like', 'patient-%')->pluck('uuid')->toArray();
            $doctorRole->syncPermissions($permissions);
            $user->assignRole([$doctorRole->uuid]);
        }

        $user1 = User::create([
            'first_name' => 'Patient',
            'last_name' => 'Admin',
            'email' => 'patient@gmail.com',
            'password' => bcrypt('123456789'), // Make sure to hash passwords
            'user_type' => 'patient',
            'email_verified_at' => now(),
        ]);

        // Find the role by its name
        $patientRole = Role::where('name', 'patient')->first();

        // Assign the 'admin' role to the user
        if ($patientRole) {
            $permissions1 = Permission::where('name', 'like', 'patient-%')->pluck('uuid')->toArray();
            $patientRole->syncPermissions($permissions1);
            $user1->assignRole([$patientRole->uuid]);
        }
    }
}
