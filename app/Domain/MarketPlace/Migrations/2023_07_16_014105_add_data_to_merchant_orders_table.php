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
            $table->string('code')->after('id')->nullable();
            $table->foreignId('employee_id')->index()->nullable()->after('merchant_branch_id');
            $table->foreign('employee_id')->on('merchant_employees')->references('id')->cascadeOnUpdate()->nullOnDelete();

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
            $table->dropColumn('code');
            // $table->dropForeign('merchant_orders_employee_id_foreign');
            // $table->dropColumn('employee_id');
        });
    }
};
