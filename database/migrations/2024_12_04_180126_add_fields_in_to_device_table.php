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
        Schema::table('devices', function (Blueprint $table): void {
            $table->string('sensor_code')->nullable()->after('rfid');
            $table->string('image')->nullable()->after('sensor_code');
            $table->float('up_front_cost')->nullable()->after('image');
            $table->float('shipping_cost')->nullable()->after('up_front_cost');
            $table->float('monthly_cost')->nullable()->after('shipping_cost');
            $table->boolean('sensor_id_required')->nullable()->after('monthly_cost');
            $table->boolean('in_stock')->nullable()->after('sensor_id_required');
            $table->boolean('virtual')->nullable()->after('in_stock');
            $table->boolean('deprecated')->nullable()->after('virtual');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('devices', function (Blueprint $table): void {
            $table->dropColumn('sensor_code');
            $table->dropColumn('image');
            $table->dropColumn('up_front_cost');
            $table->dropColumn('shipping_cost');
            $table->dropColumn('monthly_cost');
            $table->dropColumn('sensor_id_required');
            $table->dropColumn('in_stock');
            $table->dropColumn('virtual');
            $table->dropColumn('deprecated');
        });
    }
};
