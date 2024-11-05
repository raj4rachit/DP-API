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
        Schema::create('subscriptions', function (Blueprint $table): void {
            $table->uuid()->primary();
            $table->foreignUuid('user_id')->references('uuid')->on('users')->onDelete('cascade');
            $table->foreignUuid('package_id')->references('uuid')->on('packages')->onDelete('cascade');
            $table->string('name');
            $table->double('patient_charge', 8, 4)->default(0);
            $table->integer('patient_count')->default(0);
            $table->double('amount')->default(0);
            $table->date('start_date');
            $table->date('end_date');
            $table->foreignUuid('payment_transaction_id')->nullable()->references('uuid')->on('payment_transactions')->onDelete('cascade');
            $table->unique(['user_id', 'start_date'], 'subscription');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
