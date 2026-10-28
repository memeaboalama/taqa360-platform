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
        Schema::table('merchant_services', function (Blueprint $table) {
            $table->dropForeign(['merchant_category_id']);
            $table->dropColumn('merchant_category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('merchant_services', function (Blueprint $table) {

        });
    }
};
