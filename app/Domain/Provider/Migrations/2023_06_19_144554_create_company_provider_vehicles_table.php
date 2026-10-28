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
        Schema::create('company_provider_vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->cascadeOnUpdate();
            $table->foreignId('provider_id')->nullable()->constrained()->cascadeOnUpdate();
            $table->foreignId('vehicle_id')->nullable()->constrained()->cascadeOnUpdate();
            $table->timestamp('drafted_at')->nullable()->comment('( time vehicle change form provider to another )');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_provider_vehicles');
    }
};
