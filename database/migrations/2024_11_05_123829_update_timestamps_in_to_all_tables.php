<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    protected $tables = [
        'users', 'diseases', 'insurance_vendors', 'insurances', 'patients', 'doctors', 'patient_doctor', 'patient_insurance', 'pathology_labs', 'pathologies', 'pathology_reports', 'allergies', 'symptoms', 'doctor_specializations', 'patient_symptom', 'patient_allergy', 'permissions', 'roles', 'patient_medical_histories', 'device_vendors', 'devices', 'hospitals', 'specialization_doctor', 'reports', 'labs',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->tables as $table) {
            // Step 1: Update NULL values to CURRENT_TIMESTAMP
            DB::table($table)
                ->whereNull('created_at')
                ->update(['created_at' => DB::raw('CURRENT_TIMESTAMP')]);

            DB::table($table)
                ->whereNull('updated_at')
                ->update(['updated_at' => DB::raw('CURRENT_TIMESTAMP')]);

            // Step 2: Alter the columns to set defaults and NOT NULL
            Schema::table($table, function (Blueprint $table): void {
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->change();
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $table): void {
                $table->timestamp('created_at')->nullable()->change();
                $table->timestamp('updated_at')->nullable()->change();
            });
        }
    }
};
