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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->index()->nullable();
            $table->foreignId('item_id')->index()->nullable();
//            $table->text('additional')->nullable();
//            $table->float('additional_total')->nullable();
//            $table->float('item_price');
            $table->integer('qty');
//            $table->float('total');
            $table->foreign('cart_id')->on('carts')->references('id')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('item_id')->on('items')->references('id')->cascadeOnUpdate()->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
