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
        Schema::create('distracts', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->foreignId('city_id')->index()->constrained()->cascadeOnUpdate();
            $table->foreignId('country_id')->index()->constrained()->cascadeOnUpdate();
            $table->enum('status',['active','inactive'])->default('active');
            $table->integer('priority')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distracts');
    }
};
