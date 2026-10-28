<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The activity log models (ProjectActivityLog / TaskActivityLog) and their
 * relations already existed, but there was no table behind them and the API
 * resources returned a hardcoded empty array, so the "سجل الأحداث" tab could
 * never show anything.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('project_activity_logs')) {
            Schema::create('project_activity_logs', function (Blueprint $table) {
                $table->id();
                $table->uuid('uuid')->unique();
                $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
                $table->string('message_key');
                $table->json('message_payload')->nullable();
                $table->string('actor')->nullable();
                $table->timestamp('timestamp')->nullable();
                $table->timestamps();

                $table->index(['project_id', 'timestamp']);
            });
        }

        if (! Schema::hasTable('task_activity_logs')) {
            Schema::create('task_activity_logs', function (Blueprint $table) {
                $table->id();
                $table->uuid('uuid')->unique();
                $table->foreignId('task_id')->constrained('tasks')->cascadeOnDelete();
                $table->string('message_key');
                $table->json('message_payload')->nullable();
                $table->string('actor_name')->nullable();
                $table->timestamp('timestamp')->nullable();
                $table->timestamps();

                $table->index(['task_id', 'timestamp']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('task_activity_logs');
        Schema::dropIfExists('project_activity_logs');
    }
};
