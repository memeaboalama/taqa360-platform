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
        Schema::create('merchant_branches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('merchant_id')->index()->constrained()->cascadeOnUpdate();
            $table->text('title')->nullable();
            $table->text('description')->nullable();
            $table->enum('type',['fixed','mobile'])->default('fixed');
            $table->boolean('accept_multi_requests_at_same_time')->default(true);
            $table->boolean('auto_accept')->default(false)->comment("default is inactive");
            $table->boolean('has_scheduled_services')->default(false);
            $table->text('address_name')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->foreignId('merchant_service_id')->index()->nullable();
            $table->foreignId('city_id')->index()->constrained()->cascadeOnUpdate();
            $table->foreignId('distract_id')->index()->constrained()->cascadeOnUpdate();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchant_branches');
    }
};
