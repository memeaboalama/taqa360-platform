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
       
            Schema::create('wallet_coupon_merchants', function (Blueprint $table) {
                $table->id();
                $table->foreignId('wallet_coupon_id')->constrained('wallet_coupons', 'id')->cascadeOnDelete();
                $table->foreignId('merchant_id')->constrained('merchants', 'id')->cascadeOnDelete();
                $table->timestamps();
            });
    
    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallet_coupon_merchants');
    }
};
