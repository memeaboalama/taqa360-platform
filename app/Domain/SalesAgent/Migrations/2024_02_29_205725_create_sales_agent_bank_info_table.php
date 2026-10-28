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
        Schema::dropIfExists('sales_agent_bank_info');
        Schema::create('sales_agent_bank_info', function (Blueprint $table) {
            $table->id();
            $table->string('name_on_card');
            $table->text('bank_name')->nullable();
            $table->string('card_number');
            $table->string('cvv');
            $table->string('expire_month');
            $table->string('expire_year');
            $table->foreignId('bank_id')->nullable();
            $table->foreign('bank_id')->on('banks')->references('id')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('sales_agent_id')->nullable();
            $table->foreign('sales_agent_id')->on('sales_agents')->references('id')->cascadeOnUpdate()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_agents_bank_info');
    }
};
