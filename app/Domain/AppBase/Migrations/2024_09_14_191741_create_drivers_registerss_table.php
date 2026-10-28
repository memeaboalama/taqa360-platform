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
        Schema::create('drivers_registers', function (Blueprint $table) {
            $table->id('id');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('password')->nullable();
            $table->string('dialcode',3)->default('966');
            $table->string('country_name',50)->default('Saudi Arabia ,(المملكة العربية السعودية)');
            $table->integer('region_id')->nullable();
            $table->integer('country_id')->nullable();
            $table->integer('city_id')->nullable();
            $table->integer('district_id')->nullable();
            $table->string('country_code',3)->default('sa');
            $table->timestamp('last_logged_in_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('network_status')->default(true);
            $table->enum('status',['active','inactive'])->default('inactive');
            $table->enum('IdentityTypeId',['NV25GlPuOnQ=','oIcaYzeDfQQ='])->default('NV25GlPuOnQ=');
            $table->integer('Id_number')->nullable();
            $table->timestamp('Id_expire_date')->nullable();
            $table->timestamp('dateOfBirth')->nullable();
            $table->integer('car_type_id')->nullable();
            $table->string('carNumber',255)->nullable();
            $table->string('iban',255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers_registers');
    }
};
