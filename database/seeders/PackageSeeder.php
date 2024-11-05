<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\V1\Package\Models\Package;

final class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $package = new Package();
        $package->name = 'Pro Package';
        $package->description = 'Pro Package';
        $package->total_patients = 50;
        $package->patient_charge = 5;
        $package->is_default = '1';
        $package->status = 'Active';
        $package->save();
    }
}
