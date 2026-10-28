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
            $table->float('vat')->after('auth_page_image')->nullable();
            $table->float('restaurant_search_capacity')->after('vat')->nullable();
            $table->float('driver_search_capacity')->after('restaurant_search_capacity')->nullable();
            $table->float('delivery_km')->after('driver_search_capacity')->nullable();
            $table->float('delivery_fees')->after('delivery_km')->nullable();
            $table->float('extra_km_fees')->after('delivery_fees')->nullable();

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
            $table->dropColumn('vat');
            $table->dropColumn('restaurant_search_capacity');
            $table->dropColumn('driver_search_capacity');
            $table->dropColumn('delivery_km');
            $table->dropColumn('delivery_fees');
            $table->dropColumn('extra_km_fees');
        });
    }
};
