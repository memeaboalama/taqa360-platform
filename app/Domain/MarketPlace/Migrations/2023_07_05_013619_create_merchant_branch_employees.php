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
        Schema::create('merchant_branch_employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('merchant_branch_id')->index()->nullable();
            $table->foreignId('merchant_employee_id')->index()->nullable();
            $table->foreign('merchant_branch_id')->on('merchant_branches')->references('id')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('merchant_employee_id')->on('merchant_employees')->references('id')->cascadeOnUpdate()->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchant_branch_employees');
    }
};
