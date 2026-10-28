<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cart_service_addons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->index()->nullable();
            $table->foreign('cart_id')->on('carts')->references('id')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('merchant_service_id')->index()->nullable();
            $table->foreign('merchant_service_id')->on('merchant_services')->references('id')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('cart_service_id')->index()->nullable();
            $table->foreign('cart_service_id')->on('cart_services')->references('id')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('addon_category_id')->index()->nullable();
            $table->foreign('addon_category_id')->on('merchant_service_addons_category')->references('id')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('service_addon_id')->index()->nullable();
            $table->foreign('service_addon_id')->on('merchant_service_addons')->references('id')->cascadeOnUpdate()->nullOnDelete();
            $table->integer('quantity')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_service_addons');
    }
};
