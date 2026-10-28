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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->text('note')->nullable();
            $table->foreignId('merchant_id')->index()->nullable();
            $table->foreign('merchant_id')->on('merchants')->references('id')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('user_id')->index()->nullable();
            $table->foreign('user_id')->on('users')->references('id')->cascadeOnUpdate()->nullOnDelete();
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
        Schema::dropIfExists('carts');
    }
};
