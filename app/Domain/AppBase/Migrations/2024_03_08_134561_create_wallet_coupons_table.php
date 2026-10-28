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
        Schema::create('wallet_coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->integer('usages_left')->default(1);
            $table->float('min_order_value')->nullable();
            $table->float('max_amount')->nullable();
            $table->enum('type', ['flat', 'percent'])->default('flat');
            $table->integer('limit_per_user')->default(1);
            $table->enum('type_amount', ['order', 'delivery'])->default('order');
            $table->float('amount_value');
            $table->foreignId('sales_agent_id');
            $table->boolean('is_custom')->default(false);
            $table->enum('status',['active','inactive'])->default('inactive');
            $table->dateTime('expired_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_coupons');
    }
};