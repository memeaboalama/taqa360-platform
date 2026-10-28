<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       
            Schema::create('promocode_merchants_choice', function (Blueprint $table) {
                $table->id();
                $table->foreignId('promocode_id')->constrained('promocodes', 'id')->cascadeOnDelete();
                $table->foreignId('merchant_id')->constrained('merchants', 'id')->cascadeOnDelete();
                $table->timestamps();
            });
    
    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promocode_merchants_choice');
    }
};
