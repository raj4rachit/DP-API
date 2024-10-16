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
        Schema::create('patient_symptom', function (Blueprint $table) {
            $table->uuid('patient_id'); // Foreign key for patients
            $table->uuid('symptom_id'); // Foreign key for symptoms
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('patient_id')->references('uuid')->on('patients')->onDelete('cascade');
            $table->foreign('symptom_id')->references('uuid')->on('symptoms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_symptom');
    }
};
