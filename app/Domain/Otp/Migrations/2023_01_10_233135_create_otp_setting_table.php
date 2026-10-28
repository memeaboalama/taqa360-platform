<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('otp_setting', function (Blueprint $table) {
            $table->id();
            $table->string('user_name');
            $table->string('api_key');
            $table->string('sender_id');
            $table->string('service_name')->nullable();
            $table->boolean('default');
            $table->string('status',50)->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('otp_setting');
    }
};
