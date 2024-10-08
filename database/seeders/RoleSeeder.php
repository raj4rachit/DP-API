<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Modules\V1\User\Enums\RoleEnum;
use Modules\V1\User\Models\Role;
use Spatie\Permission\Models\Permission;

final class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = RoleEnum::names();

        foreach ($roles as $name) {
            $role = Role::firstOrCreate(['uuid' => Str::uuid(),'name' => $name]);
            if (RoleEnum::ADMIN === $name) {
                $permissions = Permission::pluck('id', 'id')->all();
                $role->syncPermissions($permissions);
            }
        }
    }
}
