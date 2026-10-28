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
        Schema::create('towing_cities', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->point('location', ['SRID' => 4326])->notNull();
            $table->integer('priority')->default(1);
            $table->enum('can_pickup',['active','inactive'])->default('active');
            $table->enum('can_dropoff',['active','inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('towing_cities');
    }
};
