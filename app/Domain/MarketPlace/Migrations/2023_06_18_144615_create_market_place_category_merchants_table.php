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
        Schema::create('market_place_category_merchants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('merchant_id')->index()->nullable();
            $table->foreign('merchant_id')->on('merchants')->references('id')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('market_category_id')->index()->nullable();
            $table->foreign('market_category_id')->on('market_place_categories')->references('id')->cascadeOnUpdate()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('market_place_category_merchants');
    }
};
