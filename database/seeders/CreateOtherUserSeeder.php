<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Modules\V1\User\Models\Role;
use Modules\V1\User\Models\User;
use Spatie\Permission\Models\Permission;

class CreateOtherUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Doctor',
            'email' => 'doctor@gmail.com',
            'password' => bcrypt('123456789'), // Make sure to hash passwords
            'user_type' => 'doctor',
            'email_verified_at' => now(),
        ]);

        // Find the role by its name
        $adminRole = Role::where('name', 'doctor')->first();

        // Assign the 'admin' role to the user
        if ($adminRole) {
            $permissions = Permission::pluck('uuid','uuid')->where('name','full-access');
            $adminRole->syncPermissions($permissions);
            $user->assignRole([$adminRole->uuid]);
        } else {
            $role = Role::create(['uuid' => Str::uuid(),'name' => 'Admin']);
            $permissions = Permission::pluck('uuid','uuid')->where('name','full-access');
            $role->syncPermissions($permissions);
            $user->assignRole([$role->uuid]);
        }
    }
}
