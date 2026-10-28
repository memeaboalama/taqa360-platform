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
        Schema::create('educator_bank_info', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('educator_id')->nullable();
            $table->string('iban');
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('bank_name');
            $table->string('bank_country');
            $table->string('bank_code');
            $table->boolean('is_approved')->default(false);

            $table->foreign('educator_id')->on('educators')->references('id')->cascadeOnUpdate()->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('educator_bank_info');
    }
};
