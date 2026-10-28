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
        Schema::create('towing_lines', function (Blueprint $table) {
            $table->id();
            $table->text('title')->nullable();
            $table->bigInteger('from_towing_city_id')->unsigned()->index();
            $table->foreign('from_towing_city_id')
                ->references('id')
                ->on('towing_cities')
                ->onUpdate('cascade');

            $table->bigInteger('to_towing_city_id')->unsigned()->index();
            $table->foreign('to_towing_city_id')
                ->references('id')
                ->on('towing_cities')
                ->onUpdate('cascade');

            $table->bigInteger('towing_type_id')->unsigned()->index();
            $table->foreign('towing_type_id')
                ->references('id')
                ->on('towing_types')
                ->onUpdate('cascade');

            $table->decimal('price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('towing_lines');
    }
};
