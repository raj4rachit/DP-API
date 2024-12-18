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
        Schema::create('patient_allergy', function (Blueprint $table) {
            $table->uuid('patient_id'); // Foreign key for patients
            $table->uuid('allergy_id'); // Foreign key for allergies
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('patient_id')->references('uuid')->on('patients')->onDelete('cascade');
            $table->foreign('allergy_id')->references('uuid')->on('allergies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_allergy');
    }
};
