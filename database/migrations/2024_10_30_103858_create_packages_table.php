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
        Schema::create('packages', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->integer('total_patients')->default(50);
            $table->float('patient_charge')->default(5.00);
            $table->integer('is_default')->default(0);
            $table->enum('status', ['Active', 'Inactive', 'Canceled'])->default('Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
