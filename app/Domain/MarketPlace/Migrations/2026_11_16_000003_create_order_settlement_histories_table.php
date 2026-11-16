<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_settlement_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('settlement_id')->constrained('order_settlements')->cascadeOnDelete();
            $table->string('actor_type')->nullable();
            $table->unsignedBigInteger('actor_id')->nullable();
            $table->string('action', 30);
            $table->string('status_from', 30)->nullable();
            $table->string('status_to', 30);
            $table->text('note')->nullable();
            $table->json('snapshot')->nullable();
            $table->timestamp('created_at');
            $table->index(['settlement_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_settlement_histories');
    }
};
