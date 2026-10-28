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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->integer('usages_left')->default(1);
            $table->integer('limit_per_user')->default(1);
            $table->float('min_order_value')->nullable();
            $table->float('max_discount')->nullable();
            $table->integer('period')->default(1);
            $table->enum('type', ['flat', 'percent']);
            $table->enum('type_discount', ['order','delivery','total']);
            $table->string('note',250);
            $table->bigInteger('merchant_id')->nullable();
            $table->tinyInteger('is_custom')->default(0);
            $table->float('discount_value')->nullable();
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
        Schema::dropIfExists('offers');
    }
};
