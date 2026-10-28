<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('task_notes', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->morphs('creatable');
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->text('content');
            $table->string('content_type')->default('text');
            $table->timestamp('sent_at');
            $table->timestamp('modified_at')->nullable();
            $table->timestamps();

            $table->index(['task_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_notes');
    }
};
