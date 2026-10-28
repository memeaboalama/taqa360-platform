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
        Schema::dropIfExists('cashback_merchants_choice');
        Schema::create('cashback_merchants_choice', function (Blueprint $table) {
            $table->id();
            $table->foreignId('merchant_id')->constrained('merchants', 'id')->cascadeOnDelete();
            $table->foreignId('cashback_id')->constrained('cash_backs', 'id')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cashback_merchants_choice');
    }
};
