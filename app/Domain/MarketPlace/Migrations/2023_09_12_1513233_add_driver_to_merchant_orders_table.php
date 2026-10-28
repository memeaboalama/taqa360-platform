<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchant_orders', function (Blueprint $table) {

            $table->foreignId('driver_id')->index()->nullable()->after('employee_id');
            $table->foreign('driver_id')->references('id')->on('drivers')
                ->cascadeOnUpdate()->nullOnDelete();
            $table->boolean('has_assign_driver')->after('driver_id')->default(0);

            $table->foreignId('assign_driver_id')->index()->nullable()->after('has_assign_driver');
            $table->foreign('assign_driver_id')->references('id')->on('drivers')
                ->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('merchant_orders', function (Blueprint $table) {

            $table->dropForeign('merchant_orders_driver_id_foreign');
            $table->dropColumn('driver_id');
            $table->dropForeign('merchant_orders_employee_id_foreign');
            $table->dropColumn('employee_id');
            $table->dropColumn('has_assign_driver');
            $table->dropForeign('merchant_orders_assign_driver_id_foreign');
            $table->dropColumn('assign_driver_id');
        });
    }
};
