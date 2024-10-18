<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $table): void {
            $table->uuid()->primary()->unique();
            $table->uuid('device_vendor_id');
            $table->string('api_key')->unique();
            $table->string('device_type');
            $table->enum('device_sim', ['Yes', 'No'])->default('No');
            $table->string('secret_key')->nullable();
            $table->string('device_model')->nullable();
            $table->enum('rfid', ['Yes', 'No'])->default('No');
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->timestamps();

            // Foreign Key Constraint
            $table->foreign('device_vendor_id')->references('uuid')->on('device_vendors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
