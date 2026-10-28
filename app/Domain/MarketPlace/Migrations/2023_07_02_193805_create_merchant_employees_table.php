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
        Schema::create('merchant_employees', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('password');
            $table->string('country_name', 50)->default('Saudi Arabia ,(المملكة العربية السعودية)');
            $table->string('country_code', 3)->default('sa');
            $table->string('dialcode', 3)->default('966');
            $table->string('status')->nullable();
            $table->timestamp('last_logged_in_at')->nullable();
            $table->foreignId('merchant_id')->index()->constrained()->cascadeOnUpdate();
            $table->foreignId('country_id')->index()->constrained()->cascadeOnUpdate();
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchant_employees');
    }
};
