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
        Schema::create('providers_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_category_id')->index()->constrained()->cascadeOnUpdate();
            $table->foreignId('provider_main_service_id')->index()->constrained()->cascadeOnUpdate();
            $table->foreignId('provider_sub_service_id')->index()->constrained()->cascadeOnUpdate();
            $table->string('providerable_type');
            $table->integer('providerable_id');
            $table->enum('status',['active','inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('providers_services');
    }
};
