<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_id');
            $table->string('status');
            $table->bigInteger('amount');
            $table->integer('fee')->nullable();
            $table->string('currency')->nullable();

            $table->integer('refunded')->nullable();
            $table->string('refundedAt')->nullable();

            $table->integer('captured')->nullable();
            $table->string('capturedAt')->nullable();

            $table->string('voidedAt')->nullable();

            $table->text('description')->nullable();
            $table->string('amountFormat')->nullable();
            $table->string('feeFormat')->nullable();
            $table->string('refundedFormat')->nullable();
            $table->string('capturedFormat')->nullable();

            $table->string('invoiceId')->nullable();
            $table->string('ip')->nullable();
            $table->string('callbackUrl')->nullable();
            $table->string('createdAt')->nullable();
            $table->string('updatedAt')->nullable();

            $table->text('metadata')->nullable();

            $table->string('source_type')->nullable();
            $table->string('source_company')->nullable();
            $table->string('source_name')->nullable();
            $table->string('source_number')->nullable();
            $table->string('source_message')->nullable();
            $table->string('source_transaction_url')->nullable();
            $table->string('source_gateway_id')->nullable();
            $table->string('source_reference_number')->nullable();

            // user info
            $table->foreignId('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->morphs('payable');
            $table->string('channel')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
