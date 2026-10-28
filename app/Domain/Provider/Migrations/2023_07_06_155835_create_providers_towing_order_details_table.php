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
        Schema::create('provider_towing_order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index()->constrained()->cascadeOnUpdate();
            $table->foreignId('provider_order_id')->index()->constrained()->cascadeOnUpdate();
            $table->string('towing_type_title');
            $table->point('pickup_address');
            $table->point('dropoff_address');
            $table->string('pickup_address_title',300)->nullable();
            $table->string('dropoff_address_title',300)->nullable();
            $table->point('final_drop_off_address')->nullable();
            $table->timestamp('start_picking_at')->nullable();
            $table->timestamp('dropoff_at')->nullable();
            $table->decimal('price_per_km',2)->default(0);
            $table->decimal('pricer_per_minute',2)->default(0);
            $table->decimal('total_distance_in_km',2)->default(0);
            $table->decimal('total_time_in_minutes',2)->default(0);
            $table->decimal('total_price',2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provider_towing_order_details');
    }
};
