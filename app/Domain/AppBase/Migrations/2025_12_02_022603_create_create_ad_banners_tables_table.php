<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ad_banners', function (Blueprint $table) {
            $table->id();

            $table->foreignId('placement_id')->constrained('ad_placements')->onDelete('cascade');
            $table->string('name');
            $table->json('title')->nullable();
            $table->json('subtitle')->nullable();
            $table->json('content');
            $table->json('click_action')->nullable();
            $table->string('target', 20)->default('_self');
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();

            $table->enum('status', ['active','inactive','scheduled','expired','draft'])->default('draft')->index();
            $table->unsignedSmallInteger('priority')->default(0);
            $table->unsignedBigInteger('views_count')->default(0);
            $table->string('ip')->nullable();
            $table->string('session_id')->nullable();
            $table->string('user_agent')->nullable();
            $table->unsignedBigInteger('clicks_count')->default(0);

            $table->json('meta')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['placement_id', 'status', 'priority'], 'active_banners');
            $table->index(['start_date', 'end_date'], 'scheduled_banners');
            $table->index('created_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ad_banners');
    }
};
