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
        Schema::create('insurance_vendors', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Vendor name
            $table->string('contact_email')->nullable(); // Contact email
            $table->string('contact_phone')->nullable(); // Contact phone number
            $table->string('address')->nullable(); // Vendor address
            $table->text('description')->nullable(); // Description of the vendor
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insurance_vendors');
    }
};
