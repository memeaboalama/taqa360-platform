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
        Schema::table('wallet_coupons_recharges', function (Blueprint $table) {
            $table->enum('wallet_type', ['Main', 'custom'])->default('Main')->after('wallet_coupon_id');
            $table->foreignId('wallet_id')->after('wallet_coupon_id')->nullable()->constrained('wallets', 'id')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wallet_coupons_recharges', function (Blueprint $table) {
            $table->dropColumn('wallet_type');
            $table->dropForeign('wallet_coupons_recharges_wallet_id_foreign');
            $table->dropColumn('wallet_id');
        });
    }
};
