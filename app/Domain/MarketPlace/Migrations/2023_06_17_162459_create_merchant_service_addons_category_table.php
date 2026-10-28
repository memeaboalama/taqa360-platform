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
        Schema::create('merchant_service_addons_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('merchant_id')->index()->constrained()->cascadeOnUpdate();
            $table->foreignId('merchant_service_id')->index()->constrained()->cascadeOnUpdate();
            $table->text('title');
            $table->string('status');
            $table->string('option_type');
            $table->integer('min_required')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchant_service_addons_category');
    }
};
