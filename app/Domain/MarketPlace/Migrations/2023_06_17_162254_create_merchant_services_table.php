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
        Schema::create('merchant_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('merchant_id')->index()->constrained()->cascadeOnUpdate();
            $table->foreignId('merchant_category_id')->index()->constrained()->cascadeOnUpdate();
            // $table->foreignId('currency_id')->index()->constrained()->cascadeOnUpdate();
            $table->text('title')->nullable();
            $table->text('description')->nullable();
            $table->float('default_price')->default(0);
            $table->string('status');
            // $table->boolean('is_scheduled')->default(false);
            // $table->integer('working_minutes')->default(0);
            // $table->integer('requests_per_hour_for_same_service')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchant_services');
    }
};
