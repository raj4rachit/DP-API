<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\V1\User\Models\Role;
use Modules\V1\User\Models\User;
use Spatie\Permission\Models\Permission;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456789'),
            'user_type' => 'admin',
            'email_verified_at' => Carbon::now(),
        ]);

        // Find the role by its name
        $adminRole = Role::where('name', 'admin')->first();

        // Assign the 'admin' role to the user
        if ($adminRole) {
            $user->assignRole([$adminRole->id]);
        } else {
            $role = Role::create(['name' => 'Admin']);
            $permissions = Permission::pluck('id','id')->all();
            $role->syncPermissions($permissions);
            $user->assignRole([$role->id]);
        }
    }
}
