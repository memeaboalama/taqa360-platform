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
        Schema::create('web_cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->nullable()->index();
            $table->foreign('cart_id')->references('id')->on('web_carts')->cascadeOnUpdate()->nullOnDelete();

            $table->foreignId('item_id')->nullable()->index();
            $table->foreign('item_id')->references('id')->on('items')->cascadeOnUpdate()->nullOnDelete();

            $table->integer('qty')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('web_cart_items');
    }
};
