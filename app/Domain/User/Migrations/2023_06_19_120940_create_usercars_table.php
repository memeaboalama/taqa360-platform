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
        Schema::create('user_cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index()->constrained()->cascadeOnUpdate();
            $table->foreignId('brand_id')->index()->constrained()->cascadeOnUpdate();
            $table->foreignId('model_id')->index()->constrained()->cascadeOnUpdate();
            $table->integer('year');
            $table->string('plate_number');
            $table->string('fuel_type')->nullable();
            $table->string('oil_type')->nullable();
            $table->string('default')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_cars');
    }
};
