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
        Schema::create('diseases', function (Blueprint $table): void {
            $table->uuid()->primary()->unique();
            $table->string('name')->unique(); // Disease name
            $table->text('description')->nullable(); // Detailed description of the disease
            $table->text('symptoms')->nullable(); // List of symptoms
            $table->text('treatment')->nullable(); // List of treatment methods
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diseases');
    }
};
