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
        Schema::create('educator_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('educator_id')->constrained()->onDelete('cascade');
            $table->foreignId('skill_id')->nullable()->constrained('skills')->onDelete('set null');
            $table->string('custom_name')->nullable();
            $table->boolean('is_custom')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('educator_skills');
    }
};
