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
        Schema::create('provider_sub_services', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->text('description')->nullable();
            $table->decimal('price')->nullable();
            $table->foreignId('provider_main_service_id')->index()->constrained()->cascadeOnUpdate();
            $table->foreignId('provider_category_id')->constrained()->cascadeOnUpdate();
            $table->foreignId('main_category_id')->constrained()->cascadeOnUpdate();
            $table->enum('schedulable',['active','inactive'])->default('active');
            $table->enum('increasable',['active','inactive'])->default('active')->comment('inactive means it is fixed price and driver cannot change price');
            $table->enum('strategy',['normal','towing'])->default('normal');
            // if strategy is towing
            $table->enum('towing_domain',['inside_city','between_cities'])->nullable();
            $table->foreignId('towing_type_id')->nullable()->constrained()->cascadeOnUpdate();
            $table->decimal('towing_open_price')->nullable();
            $table->decimal('towing_price_per_km')->nullable();
            $table->decimal('towing_price_per_minute')->nullable();
            $table->enum('status',['active','inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provider_sub_services');
    }
};
