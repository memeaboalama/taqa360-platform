<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ad_placements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('ad_pages')->onDelete('cascade');
            $table->json('name');
            $table->string('key', 100);
            $table->string('location', 50);
            $table->enum('type', ['slider','static_image','grid_banners','video_banner','carousel','html_widget'])->default('static_image')->index();
            $table->json('settings');
            $table->enum('status', ['active', 'inactive', 'draft'])->default('draft')->index();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->json('meta')->nullable();

            $table->timestamps();
            $table->softDeletes();


            $table->unique(['page_id', 'key'], 'unique_placement_per_page');
            $table->index(['page_id', 'status', 'sort_order'], 'active_placements');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ad_placements');
    }
};
