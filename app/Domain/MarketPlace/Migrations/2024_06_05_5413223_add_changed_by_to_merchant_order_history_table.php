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
        Schema::table('merchant_order_history', function (Blueprint $table) {
            $table->dropForeign('merchant_order_history_driver_id_foreign');
            $table->dropColumn('driver_id');
            // $table->dropColumn('changeable_type');
            // $table->dropColumn('changeable_id');
            // $table->index('changeable');
            // $table->morphs('changeable');

        });
        Schema::table('merchant_order_history', function (Blueprint $table) {
            // $table->dropForeign('merchant_order_history_driver_id_foreign');
            // $table->dropColumn('driver_id');
            // $table->dropColumn('changeable_type');
            // $table->dropColumn('changeable_id');
            // // $table->index('changeable');
            $table->morphs('changeable');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('merchant_order_history', function (Blueprint $table) {
            $table->dropMorphs('changeable');
            $table->unsignedBigInteger('driver_id')->nullable();
        });
    }
};
