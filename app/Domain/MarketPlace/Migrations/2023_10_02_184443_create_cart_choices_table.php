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
        Schema::create('cart_choices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->index()->nullable();
            $table->foreignId('item_id')->index()->nullable();
            $table->foreignId('addition_group_id')->index()->nullable();
            $table->foreignId('choice_id')->index()->nullable();
            $table->foreignId('cart_item_id')->index()->nullable();
            $table->integer('qty');
            $table->foreign('cart_id')->on('carts')->references('id')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('item_id')->on('items')->references('id')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('addition_group_id')->on('addition_groups')->references('id')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('choice_id')->on('choices')->references('id')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('cart_item_id')->on('cart_items')->references('id')->cascadeOnUpdate()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_choices');
    }
};
