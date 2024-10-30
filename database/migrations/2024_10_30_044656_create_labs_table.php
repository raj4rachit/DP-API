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
        Schema::create('labs', function (Blueprint $table): void {
            $table->uuid()->primary()->unique();
            $table->uuid('user_id'); // Foreign key for users table
            $table->string('name')->unique();
            $table->longText('address')->nullable();
            $table->string('phone')->nullable();
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
        Schema::dropIfExists('labs');
    }
};
