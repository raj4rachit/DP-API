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
        Schema::create('doctor_specialization', function (Blueprint $table) {
            $table->uuid('doctor_id'); // Foreign key for doctors
            $table->uuid('specialization_id'); // Foreign key for specializations
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('doctor_id')->references('uuid')->on('doctors')->onDelete('cascade');
            $table->foreign('specialization_id')->references('uuid')->on('doctor_specializations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_specialization');
    }
};
