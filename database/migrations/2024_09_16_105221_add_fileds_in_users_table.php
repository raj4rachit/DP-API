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
        Schema::table('users', function (Blueprint $table) {
            $table->string('mobile_no')->nullable()->default(null)->index()->after('role_id');
            $table->string('gender')->default('M')->index()->after('mobile_no');
            $table->string('dob')->nullable()->default(null)->index()->after('gender');
            $table->string('address')->nullable()->default(null)->index()->after('dob');
            $table->string('profile_image')->nullable()->default(null)->index()->after('address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('mobile_no');
            $table->dropColumn('gender');
            $table->dropColumn('dob');
            $table->dropColumn('address');
            $table->dropColumn('profile_image');
        });
    }
};
