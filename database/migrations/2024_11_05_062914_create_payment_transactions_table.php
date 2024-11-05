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
        Schema::create('payment_transactions', function (Blueprint $table): void {
            $table->uuid()->primary();
            $table->foreignUuid('user_id')->references('uuid')->on('users')->onDelete('cascade');
            $table->double('amount', 8, 2)->default(0);
            $table->string('payment_gateway')->nullable();
            $table->string('order_id')->comment('order_id / payment_intent_id');
            $table->string('transaction_id')->nullable(true);
            $table->enum('payment_status', [0, 1, 2])->comment('0 - failed 1 - succeed 2 - pending');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
