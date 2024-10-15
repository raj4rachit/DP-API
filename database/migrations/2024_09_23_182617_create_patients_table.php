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
        Schema::create('patients', function (Blueprint $table) {
            $table->uuid();
            $table->uuid('user_id'); // Foreign key for users table
            $table->string('arn_number');
            $table->string('id_type');
            $table->string('id_number');
            $table->date('dob');
            $table->enum('gender', ['Male', 'Female', 'Other'])->default('Male');
            $table->longText('address')->nullable();
            $table->enum('marital_status', ['Single', 'Married', 'Divorced', 'Widowed'])->default('Single');
            $table->string('primary_phone');
            $table->string('secondary_phone')->nullable();
            $table->string('home_phone')->nullable();
            $table->string('work_phone')->nullable();
            $table->json('languages'); // Store multiple languages in JSON format
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('user_id')->references('uuid')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
