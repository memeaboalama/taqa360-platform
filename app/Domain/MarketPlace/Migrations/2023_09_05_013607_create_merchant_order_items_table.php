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
        Schema::create('merchant_order_items', function (Blueprint $table) {
            $table->id();
            $table->text('item_name')->nullable();
            $table->integer('qty');
            $table->float('item_total')->default(0);
            $table->float('vat')->default(0);
            $table->float('total_after_vat')->default(0);
            $table->foreignId('merchant_id')->index()->nullable();
            $table->foreign('merchant_id')->on('merchants')->references('id')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('user_id')->index()->nullable();
            $table->foreign('user_id')->on('users')->references('id')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('order_id')->index()->nullable();
            $table->foreign('order_id')->on('merchant_orders')->references('id')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('merchant_branch_id')->index()->nullable();
            $table->foreign('merchant_branch_id')->on('merchant_branches')->references('id')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('item_id')->index()->nullable();
            $table->foreign('item_id')->on('items')->references('id')->cascadeOnUpdate()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchant_order_items');
    }
};
