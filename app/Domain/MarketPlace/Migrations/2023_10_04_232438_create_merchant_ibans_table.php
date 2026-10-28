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
        Schema::create('merchant_ibans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('merchant_id')->index()->constrained()->cascadeOnUpdate();
            $table->string('bank_name')->nullable();
            $table->string('beneficiary_full_name')->nullable();
            $table->string('iban')->nullable();
            $table->boolean('is_temp')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchant_ibans');
    }
};
