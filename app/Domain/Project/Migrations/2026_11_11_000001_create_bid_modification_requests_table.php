<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bid_modification_requests', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('bid_id')->constrained()->cascadeOnDelete();
            $table->foreignId('educator_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('PENDING');
            $table->json('previous_values');
            $table->json('requested_values');
            $table->text('decision_note')->nullable();
            $table->unsignedBigInteger('decided_by')->nullable();
            $table->timestamp('decided_at')->nullable();
            $table->timestamps();

            $table->index(['bid_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bid_modification_requests');
    }
};
