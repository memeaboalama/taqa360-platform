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
        Schema::create('merchant_order_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->index()->nullable();
            $table->foreign('order_id')->on('merchant_orders')->references('id')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('driver_id')->index()->nullable();
            $table->foreign('driver_id')->on('drivers')->references('id')->cascadeOnUpdate()->nullOnDelete();
            $table->string('action');
            $table->string('reason')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchant_order_history');
    }
};
