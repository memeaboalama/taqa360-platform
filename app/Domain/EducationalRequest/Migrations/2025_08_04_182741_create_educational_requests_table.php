<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('educational_requests', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('requester_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('academic_major_id')->nullable()->constrained('academic_majors')->nullOnDelete();
            $table->string('education_level');
            $table->string('type')->default('general');
            $table->string('status')->default('pending');
            $table->string('execution_status')->default('pending');
            $table->decimal('budget', 10, 2)->nullable();
            $table->decimal('agreed_amount', 10, 2)->nullable();
            $table->string('payment_status')->default('unpaid');
            $table->text('final_agreement')->nullable();
            $table->string('urgency_level')->default('normal');
            $table->text('additional_notes')->nullable();

            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('rejection_reason')->nullable();

            $table->foreignId('reviewed_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['requester_id', 'status']);
            $table->index(['status', 'execution_status']);
            $table->index(['title', 'education_level']);
            $table->index('created_at');
            $table->fullText(['title', 'description']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('educational_requests');
    }
};
