<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

final class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /**** Create All the Permission ****/
        $this->createPermissions();

        //clear cache
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        Artisan::call('config:clear');
        Artisan::call('cache:clear');

    }

    public function createPermissions() {

        $permissions = [
            ['name' => 'full-access'],
            ...self::permission('user'),
            ...self::permission('role'),
            ...self::permission('doctor'),
            ...self::permission('patient'),
            ...self::permission('lab'),
            ...self::permission('report'),
            ...self::permission('hospital'),
            ...self::permission('device'),
            //['name' => 'report-list'],
        ];
        $permissions = array_map(static function ($data) {
            $data['guard_name'] = 'web';
            $data['uuid'] = Str::uuid();
            return $data;
        }, $permissions);
        Permission::upsert($permissions, ['name'], ['name']);
    }

    public static function permission($prefix, array $customPermissions = [])
    {

        $list = [['name' => $prefix . '-list']];
        $create = [['name' => $prefix . '-create']];
        $edit = [['name' => $prefix . '-edit']];
        $delete = [['name' => $prefix . '-delete']];

        $finalArray = array_merge($list, $create, $edit, $delete);
        foreach ($customPermissions as $customPermission) {
            $finalArray[] = ['name' => $prefix . '-' . $customPermission];
        }

        return $finalArray;
    }
}
