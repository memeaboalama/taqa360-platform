<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('merchant_service_addons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('merchant_id')->index()->constrained()->cascadeOnUpdate();
            $table->foreignId('merchant_service_id')->index()->constrained()->cascadeOnUpdate();
            $table->foreignId('addon_category_id')->index()->nullable();
            $table->foreign('addon_category_id')->on('merchant_service_addons_category')->references('id')->cascadeOnUpdate()->nullOnDelete();
            $table->text('title');
            $table->string('status');
            $table->float('price')->default(0);
            $table->integer('working_minutes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchant_service_addons');
    }
};
