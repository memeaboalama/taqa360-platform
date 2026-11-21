<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('merchant_order_item_id')
                ->constrained('merchant_order_items')
                ->cascadeOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->string('title', 120);
            $table->text('comment');
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->unique(['item_id', 'user_id']);
            $table->index(['item_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_reviews');
    }
};
