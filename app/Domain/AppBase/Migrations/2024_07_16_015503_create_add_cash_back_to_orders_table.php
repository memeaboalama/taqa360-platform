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

        Schema::table('merchant_orders', function (Blueprint $table) {
            $table->bigInteger('cash_back_id')->after('promocode_id')->nullable();
            $table->string('cash_back',255)->after('promocode_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('merchant_orders', function (Blueprint $table) {
            $table->dropColumn('cash_back_id');
            $table->dropColumn('cash_back');
        });
    }
};