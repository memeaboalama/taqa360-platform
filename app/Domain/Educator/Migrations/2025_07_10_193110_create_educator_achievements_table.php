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
        Schema::create('educator_achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('educator_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->date('date');
            $table->text('description')->nullable();
            $table->string('organization')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('educator_achievements');
    }
};
