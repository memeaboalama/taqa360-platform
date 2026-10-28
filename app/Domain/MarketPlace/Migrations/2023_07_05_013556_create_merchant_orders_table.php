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
        Schema::create('merchant_orders', function (Blueprint $table) {
            $table->id();
            $table->float('subtotal')->default(0);
            $table->float('vat')->default(0);
            $table->float('total_after_vat')->default(0);
            $table->float('total_addon')->default(0);
            $table->float('total_service')->default(0);
            $table->float('discount')->default(0);
            $table->float('total_after_discount')->default(0);
            $table->string('channel');
            $table->string('payment_type')->nullable();
            $table->string('client_name')->nullable();
            $table->string('client_phone')->nullable();
            $table->string('client_lat')->nullable();
            $table->string('client_long')->nullable();
            $table->string('merchant_name')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('status')->nullable();
            $table->string('dialcode',3)->default('966');
            $table->boolean('schedulable')->default(false);
            $table->date('date')->nullable();
            $table->time('from')->nullable();
            $table->time('to')->nullable();
            $table->enum('order_type',['fixed','mobile']);
            $table->foreignId('merchant_id')->index()->nullable();
            $table->foreign('merchant_id')->on('merchants')->references('id')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('user_id')->index()->nullable();
            $table->foreign('user_id')->on('users')->references('id')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('address_id')->index()->nullable();
            $table->foreign('address_id')->on('user_addresses')->references('id')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('merchant_branch_id')->index()->nullable();
            $table->foreign('merchant_branch_id')->on('merchant_branches')->references('id')->cascadeOnUpdate()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchant_orders');
    }
};
