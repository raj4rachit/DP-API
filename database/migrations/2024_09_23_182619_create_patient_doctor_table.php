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
        Schema::create('patient_doctor', function (Blueprint $table) {
            $table->uuid('patient_id'); // Foreign key for patient
            $table->uuid('doctor_id'); // Foreign key for doctor
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('patient_id')->references('uuid')->on('patients')->onDelete('cascade');
            $table->foreign('doctor_id')->references('uuid')->on('doctors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_doctor');
    }
};
