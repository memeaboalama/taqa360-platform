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
        Schema::create('provider_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index()->constrained()->cascadeOnUpdate();
            $table->foreignId('company_id')->nullable()->constrained()->cascadeOnUpdate();
            $table->foreignId('provider_id')->nullable()->constrained()->cascadeOnUpdate();
            $table->foreignId('provider_sub_services_id')->constrained()->cascadeOnUpdate();
            $table->foreignId('provider_vehicle_id')->nullable();
            $table->foreignId('provider_vehicle_plate_number')->nullable();
            $table->string('provider_vehicle_type')->default('towing');
            $table->string('payment_type')->default('cash');
            $table->decimal('collected_price_by_provider',2)->default(0)->nullable();
            $table->decimal('total_order',2)->nullable();
            $table->decimal('vat',2)->nullable();
            $table->decimal('total_after_vat',2)->nullable();
            $table->string('client_name');
            $table->string('client_full_phone');
            $table->string('provider_name')->nullable();
            $table->string('company_name')->nullable();
            $table->point('client_location');
            $table->enum('schedulable',['active','inactive'])->default('inactive');
            $table->date('date')->nullable();
            $table->time('from')->nullable();
            $table->time('to')->nullable();
            $table->string('status')->default('pending');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provider_orders');
    }
};
