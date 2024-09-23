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
        Schema::create('pathology_labs', function (Blueprint $table) {
            $table->id();
            $table->string('lab_name'); // Name of the pathology lab
            $table->string('address')->nullable(); // Address of the lab
            $table->string('contact_number')->nullable(); // Contact number for the lab
            $table->string('email')->nullable(); // Email address of the lab
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pathology_labs');
    }
};
