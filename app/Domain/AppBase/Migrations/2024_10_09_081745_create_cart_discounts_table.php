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
        Schema::create('cart_discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId(column: 'order_id');
            $table->enum('type',['type_service'])->default('type_service');
            $table->decimal('amount',8,2);
            $table->double('rate',8,2);
            $table->enum('calculated_on',['company','store','delivery'])->default('store');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_discounts');
    }
};
