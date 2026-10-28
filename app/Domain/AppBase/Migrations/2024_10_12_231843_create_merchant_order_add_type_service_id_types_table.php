<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('merchant_orders', function (Blueprint $table) {
            $table->bigInteger('service_type_id')->default('1')->change();
        });
    }
    
    public function down()
    {
        Schema::table('merchant_orders', function (Blueprint $table) {
            $table->string('service_type_id')->default('with_delivery')->change();
        });
    }
};
