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
        Schema::table('merchant_orders', function (Blueprint $table) {
            $table->string('promocode')->nullable()->after('merchant_branch_id');
            $table->foreignId('promocode_id')->nullable()->after('promocode');
            $table->foreign('promocode_id')->on('promocodes')->references('id')->cascadeOnUpdate()->nullOnDelete();

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
                // $table->dropColumn('promocode');
                $table->dropForeign('merchant_orders_promocode_id_foreign');
                $table->dropColumn('promocode_id');

        });
    }
};
