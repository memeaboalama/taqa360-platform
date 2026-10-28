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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->index()->constrained()->cascadeOnUpdate();
            $table->foreignId('model_id')->index()->constrained()->cascadeOnUpdate();
            $table->morphs('vehicleable');
            $table->string('plate_number');
            $table->integer('year');
            $table->enum('is_towing',['active','inactive'])->default('active');
            $table->foreignId('towing_type_id')->nullable()->constrained()->cascadeOnUpdate();
            $table->enum('status',['active','inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
