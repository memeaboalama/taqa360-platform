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
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index()->constrained()->cascadeOnUpdate();
            $table->foreignId('country_id')->index()->constrained()->cascadeOnUpdate();
            $table->foreignId('city_id')->index()->constrained()->cascadeOnUpdate();
            $table->foreignId('distract_id')->index()->constrained()->cascadeOnUpdate();
            $table->string('title');
            $table->text('address');
            $table->string('latitude');
            $table->string('longitude');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};
