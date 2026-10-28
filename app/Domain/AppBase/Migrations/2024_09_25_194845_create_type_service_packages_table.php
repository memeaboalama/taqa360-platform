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
        Schema::create('package_type_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_service_id');
            $table->foreignId('package_id');
            $table->double('commission',8,2)->nullable();
            $table->double('discount_rate',8,2)->nullable();
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
        Schema::dropIfExists('package_type_services');
    }
};
