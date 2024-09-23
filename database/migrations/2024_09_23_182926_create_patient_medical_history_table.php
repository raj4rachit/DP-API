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
        Schema::create('patient_medical_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id'); // Foreign key for patients
            $table->unsignedBigInteger('doctor_id')->nullable(); // Foreign key for doctors (optional)
            $table->string('diagnosis'); // Medical diagnosis
            $table->text('treatment')->nullable(); // Treatment details
            $table->text('medications')->nullable(); // Medications prescribed
            $table->date('visit_date'); // Date of the visit or diagnosis
            $table->text('notes')->nullable(); // Additional notes about the condition
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_medical_history');
    }
};
