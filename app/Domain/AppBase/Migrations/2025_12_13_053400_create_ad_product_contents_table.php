<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ad_product_contents', function (Blueprint $table) {
            $table->id();

            $table->foreignId('placement_id')->constrained('ad_placements')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('items')->onDelete('cascade');

            $table->string('group_key', 50)->nullable();

            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();

            $table->enum('status', ['active', 'inactive', 'scheduled', 'expired'])->default('active')->index();
            $table->unsignedSmallInteger('priority')->default(0);

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['placement_id', 'status', 'priority'], 'active_contents');
            $table->index(['placement_id', 'group_key', 'status'], 'grouped_contents');
            $table->index('product_id');
            $table->index(['start_date', 'end_date'], 'scheduled_contents');

            $table->unique(['placement_id', 'product_id', 'group_key'], 'unique_product_per_group');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ad_product_contents');
    }
};
