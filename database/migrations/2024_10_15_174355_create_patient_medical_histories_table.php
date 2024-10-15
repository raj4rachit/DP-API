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
        Schema::create('patient_medical_histories', function (Blueprint $table) {
            $table->uuid();
            $table->uuid('patient_id'); // Foreign key to patients table
            $table->string('medical_aid')->nullable();
            $table->string('race')->nullable();
            $table->string('ethnicity')->nullable();
            $table->string('mrn_number')->nullable(); // Medical Record Number
            $table->timestamps();

            // Foreign Key Constraint
            $table->foreign('patient_id')->references('uuid')->on('patients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_medical_histories');
    }
};
