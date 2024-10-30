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
        Schema::create('lab_reports', function (Blueprint $table): void {
            $table->uuid('lab_id');
            $table->uuid('report_id');

            // Foreign key constraint
            $table->foreign('lab_id')->references('uuid')->on('labs')->onDelete('cascade');
            $table->foreign('report_id')->references('uuid')->on('reports')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_reports');
    }
};
