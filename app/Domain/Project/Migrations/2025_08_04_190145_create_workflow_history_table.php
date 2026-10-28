<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workflow_history', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('workflow_id')->constrained('workflows')->onDelete('cascade');
            $table->string('event_type');
            $table->text('message')->nullable();
            $table->nullableMorphs('actor');
            $table->json('properties')->nullable();
            $table->timestamp('created_at');

            $table->index(['workflow_id', 'created_at']);
            $table->index('event_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workflow_history');
    }
};
