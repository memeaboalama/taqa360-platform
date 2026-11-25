<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('specification_category_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('specification_id')->constrained('specifications')->cascadeOnDelete();
            $table->string('category_type', 24);
            $table->unsignedBigInteger('category_id');
            $table->timestamps();
            $table->unique(['specification_id', 'category_type', 'category_id'], 'spec_category_links_unique');
            $table->index(['category_type', 'category_id'], 'spec_category_links_lookup');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('specification_category_links');
    }
};
