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
        Schema::create('insurances', function (Blueprint $table) {
            $table->uuid()->primary()->unique();
            $table->uuid('vendor_id'); // Foreign key to insurance_vendors
            $table->string('policy_number')->unique(); // Unique policy number
            $table->string('type'); // Insurance type (e.g., health, auto, life)
            $table->date('start_date'); // Policy start date
            $table->date('end_date')->nullable(); // Policy end date
            $table->decimal('coverage_amount', 15, 2)->nullable(); // Coverage amount
            $table->decimal('premium_amount', 15, 2)->nullable(); // Premium cost
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('vendor_id')->references('uuid')->on('insurance_vendors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insurances');
    }
};
