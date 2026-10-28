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
        Schema::create('merchant_branches_work_day_hours', function (Blueprint $table) {
            $table->id();
            $table->time('from');
            $table->time('to');
            $table->string('status');
            $table->foreignId('merchant_branch_id')->index()->constrained()->cascadeOnUpdate();
            $table->foreignId('work_day_id')->index()->nullable();
            $table->foreign('work_day_id')->on('merchant_branches_work_days')->references('id')->cascadeOnUpdate()->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchant_branches_work_day_hours');
    }
};
