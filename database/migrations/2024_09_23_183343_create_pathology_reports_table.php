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
        Schema::create('pathology_reports', function (Blueprint $table) {
            $table->uuid()->primary()->unique();
            $table->uuid('patient_id');
            $table->uuid('doctor_id')->nullable();
            $table->uuid('pathology_id');
            $table->uuid('lab_id')->nullable(); // Foreign key to pathology labs
            $table->date('test_date');
            $table->text('result')->nullable();
            $table->string('report_file')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('patient_id')->references('uuid')->on('patients')->onDelete('cascade');
            $table->foreign('doctor_id')->references('uuid')->on('doctors')->onDelete('set null');
            $table->foreign('pathology_id')->references('uuid')->on('pathologies')->onDelete('cascade');
            $table->foreign('lab_id')->references('uuid')->on('pathology_labs')->onDelete('set null'); // Link to labs
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pathology_reports');
    }
};
