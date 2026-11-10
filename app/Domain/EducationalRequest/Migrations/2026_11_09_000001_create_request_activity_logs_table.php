<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('request_activity_logs')) {
            return;
        }

        Schema::create('request_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('educational_request_id')
                ->constrained('educational_requests')
                ->cascadeOnDelete();
            $table->string('activity_type');
            $table->string('from_status')->nullable();
            $table->string('to_status');
            $table->text('reason')->nullable();
            $table->string('actor_name')->nullable();
            $table->timestamps();

            $table->index(['educational_request_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('request_activity_logs');
    }
};
