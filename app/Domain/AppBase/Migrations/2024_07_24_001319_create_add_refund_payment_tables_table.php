<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('refund_payments', function (Blueprint $table) {
            $table->id();
            $table->string('refund_payment_id');
            $table->decimal('amount', 64, 0);
            $table->text('currency');
            $table->text('note');
            $table->text('type');
            $table->string('payment_id')->references('payment_id')->on('payments')->cascadeOnDelete();
            $table->bigInteger('order_id')->references('id')->on('merchant_orders')->cascadeOnDelete();
            $table->bigInteger("user_id")->references('id')->on('users')->cascadeOnDelete();
            $table->string("user_name");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refund_payment');
    }
};
