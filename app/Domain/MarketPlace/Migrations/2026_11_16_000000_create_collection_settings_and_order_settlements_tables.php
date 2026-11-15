<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('merchant_orders', function (Blueprint $table) {
            $table->json('collection_config')->nullable();
        });

        Schema::create('collection_settings', function (Blueprint $table) {
            $table->id();
            $table->string('recipient')->default('platform');
            $table->json('enabled_methods');
            $table->json('methods');
            $table->json('review_role_ids')->nullable();
            $table->json('approval_role_ids')->nullable();
            $table->json('correction_role_ids')->nullable();
            $table->timestamps();
        });

        Schema::create('order_settlements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained('merchant_orders')->cascadeOnDelete();
            $table->foreignId('driver_id')->nullable()->constrained('drivers')->nullOnDelete();
            $table->string('recipient');
            $table->string('method');
            $table->string('recipient_name');
            $table->text('instructions');
            $table->text('account_data')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('reference')->nullable();
            $table->string('proof_path')->nullable();
            $table->string('status')->default('pending_review');
            $table->foreignId('reviewed_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('order_settlement_corrections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('settlement_id')->constrained('order_settlements')->cascadeOnDelete();
            $table->foreignId('corrected_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->decimal('amount', 10, 2);
            $table->text('reason');
            $table->timestamp('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_settlement_corrections');
        Schema::dropIfExists('order_settlements');
        Schema::dropIfExists('collection_settings');
        Schema::table('merchant_orders', function (Blueprint $table) {
            $table->dropColumn('collection_config');
        });
    }
};
