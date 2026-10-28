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
        Schema::create('web_carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->index();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate()->nullOnDelete();

            $table->text('note')->nullable();
            $table->string('promocode')->nullable();

            $table->foreignId('promocode_id')->nullable()->index();
            $table->foreign('promocode_id')->references('id')->on('promocodes')->cascadeOnUpdate()->nullOnDelete();

            $table->string('cash_back')->nullable();
            $table->unsignedBigInteger('cash_back_id')->nullable();
            $table->unsignedBigInteger('service_type_id')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('web_carts');
    }
};
