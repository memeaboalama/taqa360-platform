<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Doctrine\DBAL\Types\Type;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('elm_orders', function (Blueprint $table) {
            $table->dropColumn('deliveryTime');
            $table->dropColumn('acceptanceDateTime');
        });

        Schema::table('elm_orders', function (Blueprint $table) {
            $table->timestamp('deliveryTime')->after("categoryId")->nullable();
            $table->timestamp('acceptanceDateTime')->after('deliveryTime')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('elm_orders', function (Blueprint $table) {
            $table->dropColumn('deliveryTime');
            $table->dropColumn('acceptanceDateTime');
        });
    }
};
