<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workflows', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->morphs('workable');
            $table->string('type');
            $table->foreignId('major_id')->nullable()->constrained('academic_majors')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('requester_id')->nullable()->constrained('educational_requests')->cascadeOnUpdate()->nullOnDelete();
            $table->string('creation_type')->default('private');
            $table->string('status')->default('draft');
            $table->decimal('budget', 15, 2)->nullable();
            $table->timestamp('deadline')->nullable();
            $table->string('payment_status')->default('pending');
            $table->string('urgency_level')->default('normal');
            $table->json('metadata')->nullable();
            $table->json('tags')->nullable();

            $table->integer('priority')->default(1);
            $table->foreignId('created_by')->constrained('admins');

            $table->timestamp('published_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('workflows');
    }
};
