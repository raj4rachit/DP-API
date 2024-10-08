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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_id'); // Foreign key for users table
            $table->string('specialization'); // Doctor's field of specialization
            $table->string('contact_phone')->nullable();
            $table->string('email')->nullable();
            $table->string('clinic_address')->nullable();
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
        Schema::dropIfExists('doctors');
    }
};
