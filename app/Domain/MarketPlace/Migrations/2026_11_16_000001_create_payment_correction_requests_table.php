<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_correction_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('merchant_orders')->cascadeOnDelete();
            $table->string('movement_type', 20);
            $table->unsignedBigInteger('movement_id');
            $table->text('reason');
            $table->foreignId('requested_by')->constrained('admins')->cascadeOnDelete();
            $table->string('status', 20)->default('pending');
            $table->timestamps();
            $table->index(['movement_type', 'movement_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_correction_requests');
    }
};
