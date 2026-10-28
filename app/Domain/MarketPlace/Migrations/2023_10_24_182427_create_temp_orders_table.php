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
        Schema::create('temp_orders', function (Blueprint $table) {
            $table->id();
            $table->string('status')->nullable();
            $table->boolean('has_accept')->default(false);
            $table->foreignId('order_id')->index()->nullable();
            $table->foreign('order_id')->on('merchant_orders')->references('id')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('driver_id')->index()->nullable();
            $table->foreign('driver_id')->references('id')->on('drivers')
                ->cascadeOnUpdate()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temp_orders');
    }
};
