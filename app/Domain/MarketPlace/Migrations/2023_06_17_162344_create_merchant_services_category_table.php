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
        Schema::create('merchant_services_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('merchant_id')->index()->cascadeOnUpdate();
            $table->foreignId('merchant_category_id')->index()->cascadeOnUpdate();
            $table->foreignId('merchant_service_id')->index()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchant_services_category');
    }
};
