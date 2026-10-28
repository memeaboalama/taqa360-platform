<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ad_pages', function (Blueprint $table) {
            $table->id();

            $table->json('name');
            $table->string('key', 50)->unique();
            $table->json('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active')->index();
            $table->unsignedSmallInteger('sort_order')->default(0);

            $table->json('meta')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ad_pages');
    }
};
