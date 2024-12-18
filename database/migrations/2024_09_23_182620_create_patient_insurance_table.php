<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('patient_insurance', function (Blueprint $table) {
            $table->uuid()->primary()->unique();
            $table->uuid('patient_id'); // Foreign key for patient
            $table->uuid('insurance_id'); // Foreign key for insurance
            $table->date('policy_start_date'); // Date when the policy starts
            $table->date('policy_end_date')->nullable(); // Date when the policy ends
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('patient_id')->references('uuid')->on('patients')->onDelete('cascade');
            $table->foreign('insurance_id')->references('uuid')->on('insurances')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_insurance');
    }
};
