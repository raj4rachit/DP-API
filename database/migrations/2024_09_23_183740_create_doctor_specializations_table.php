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
        Schema::create('doctor_specializations', function (Blueprint $table) {
            $table->uuid()->primary()->unique();
            $table->string('name')->unique(); // Name of the specialization (e.g., "Cardiology", "Dermatology")
            $table->string('code')->unique();
            $table->text('description')->nullable(); // Optional description of the specialization
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_specializations');
    }
};
