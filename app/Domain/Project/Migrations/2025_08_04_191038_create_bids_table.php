<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bids', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('educator_id')->constrained()->onDelete('cascade');
            $table->decimal('total_amount', 10, 2);
            $table->text('proposal');
            $table->unsignedInteger('delivery_days');
            $table->json('links');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['project_id', 'educator_id']);
            $table->index(['project_id']);
            $table->index(['educator_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bids');
    }
};
