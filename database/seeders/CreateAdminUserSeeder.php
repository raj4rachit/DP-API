<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Modules\V1\User\Models\Role;
use Modules\V1\User\Models\User;
use Spatie\Permission\Models\Permission;

final class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'first_name' => 'admin',
            'last_name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456789'), // Make sure to hash passwords
            'user_type' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Find the role by its name
        $adminRole = Role::where('name', 'admin')->first();

        // Assign the 'admin' role to the user
        if ($adminRole) {
            $permissions = Permission::where('name', 'full-access')->pluck('uuid')->toArray();
            $adminRole->syncPermissions($permissions);
            $user->assignRole([$adminRole->uuid]);
        } else {
            $role = Role::create(['uuid' => Str::uuid(), 'name' => 'Admin']);
            $permissions = Permission::where('name', 'full-access')->pluck('uuid')->toArray();
            $role->syncPermissions($permissions);
            $user->assignRole([$role->uuid]);
        }
    }
}
