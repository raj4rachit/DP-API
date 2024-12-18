<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table): void {
            $table->uuid()->primary();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->integer('total_patients')->default(50);
            $table->float('patient_charge')->default(5.00);
            $table->enum('is_default', [0, 1])->default(0)->comment('0 - no 1 - yes');
            $table->enum('status', ['Active', 'Inactive', 'Canceled'])->default('Inactive');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
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
