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
        Schema::create('merchant_phones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('merchant_id')->index()->constrained()->cascadeOnUpdate();
            $table->string('phone')->nullable();
            $table->string('dialcode',3)->default('966');
            $table->string('country_name',50)->default('Saudi Arabia ,(المملكة العربية السعودية)');
            $table->string('country_code',3)->default('sa');
            $table->boolean('is_default')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchant_phones');
    }
};
