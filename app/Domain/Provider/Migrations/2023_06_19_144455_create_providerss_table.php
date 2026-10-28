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
        Schema::create('providers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->cascadeOnUpdate();
            $table->foreignId('nationality_id')->nullable()->constrained()->cascadeOnUpdate();
            $table->foreignId('country_id')->index()->constrained()->cascadeOnUpdate();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone');
            $table->string('dialcode',3)->default('966');
            $table->string('country_name',50)->default('Saudi Arabia ,(المملكة العربية السعودية)');
            $table->string('country_code',3)->default('sa');
            $table->timestamp('last_logged_in_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->point('location', ['SRID' => 4326])->notNull();
            $table->enum('network_status',['online','offline'])->default('offline');
            $table->enum('status',['active','inactive'])->default('active');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('providers');
    }
};
