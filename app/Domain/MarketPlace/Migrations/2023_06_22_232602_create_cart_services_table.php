<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cart_services', function (Blueprint $table) {
            $table->id();
            $table->text('note')->nullable();
            $table->foreignId('cart_id')->index()->nullable();
            $table->foreign('cart_id')->on('carts')->references('id')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('merchant_service_id')->index()->nullable();
            $table->foreign('merchant_service_id')->on('merchant_services')->references('id')->cascadeOnUpdate()->nullOnDelete();
            $table->integer('quantity')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_services');
    }
};
