<?php

declare(strict_types=1);

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Modules\V1\User\Enums\RoleEnum;
use Modules\V1\User\Models\Role;

final class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = RoleEnum::names();

        foreach ($roles as $name) {
            $role = Role::firstOrCreate(['name' => $name, 'updated_at' => date_format(Carbon::now(), 'Y-m-d H:i:s'), 'created_at' => date_format(Carbon::now(), 'Y-m-d H:i:s')]);
            dd($role);
            if ($name === RoleEnum::ADMIN) {
                $permissions = Permission::pluck('id','id')->all();
                $role->syncPermissions($permissions);
            }
        }
    }
}
