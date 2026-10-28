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
            $table->float('visa_commission')->after('resend_order_to_drivers')->nullable();
            $table->float('master_commission')->after('visa_commission')->nullable();
            $table->float('mada_commission')->after('master_commission')->nullable();
            $table->float('apple_commission')->after('mada_commission')->nullable();
            $table->float('stc_commission')->after('apple_commission')->nullable();

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
            $table->dropColumn('visa_commission');
            $table->dropColumn('master_commission');
            $table->dropColumn('mada_commission');
            $table->dropColumn('apple_commission');
            $table->dropColumn('stc_commission');
        });
    }
};
