<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('general_settings', function (Blueprint $table) {
            $table->float('pending_order_expiry')->after('min_order_price')->nullable();
            $table->float('confirmed_order_expiry')->after('pending_order_expiry')->nullable();
            $table->float('resend_order_to_drivers')->after('confirmed_order_expiry')->nullable();

        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('general_settings', function (Blueprint $table) {
            $table->dropColumn('pending_order_expiry');
            $table->dropColumn('confirmed_order_expiry');
            $table->dropColumn('resend_order_to_drivers');
        });
    }
};
