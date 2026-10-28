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
        Schema::create('merchant_choices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->index()->nullable();
            $table->foreignId('item_id')->index()->nullable();
            $table->foreignId('addition_group_id')->index()->nullable();
            $table->foreignId('choice_id')->index()->nullable();
            $table->foreignId('order_item_id')->index()->nullable();
            $table->integer('qty');
            $table->foreign('order_id')->on('merchant_orders')->references('id')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('item_id')->on('items')->references('id')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('addition_group_id')->on('addition_groups')->references('id')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('choice_id')->on('choices')->references('id')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('order_item_id')->on('merchant_order_items')->references('id')->cascadeOnUpdate()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchant_choices');
    }
};
