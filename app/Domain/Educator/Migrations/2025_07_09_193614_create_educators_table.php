<?php

use App\Support\Enum\StatusEnum;
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
        Schema::create('educators', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable()->unique();
            $table->string('password')->nullable();

            $table->string('role')->nullable();
            $table->string('discipline')->nullable();
            $table->string('school_name')->nullable();

            $table->string('dialcode',3)->default('966');
            $table->string('country_name',50)->default('Saudi Arabia ,(المملكة العربية السعودية)');
            $table->string('country_code',3)->default('sa');
            $table->timestamp('last_logged_in_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();

            $table->decimal('rating', 3, 2)->default(0.00);
            $table->string('availability_status')->default(StatusEnum::ACTIVE);
            $table->string('status')->default('pending');
            $table->boolean('accepted_terms')->default(false);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'availability_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('educators');
    }
};
