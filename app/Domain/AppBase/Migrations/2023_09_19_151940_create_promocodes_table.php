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
        Schema::create('promocodes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->integer('usages_left')->default(1);
            $table->float('min_order_value')->nullable();
            $table->float('max_discount')->nullable();
            $table->enum('type', ['flat', 'percent']);
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
        Schema::dropIfExists('promocodes');
    }
};
