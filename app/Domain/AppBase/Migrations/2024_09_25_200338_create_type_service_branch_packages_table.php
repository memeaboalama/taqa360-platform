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
        Schema::create('merchant_branch_type_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_service_id');
            $table->foreignId('merchant_branch_id');
            $table->foreignId('package_id');
            $table->string('desc')->nullable();
            $table->enum('status',['active','inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchant_branch_type_services');
    }
};
