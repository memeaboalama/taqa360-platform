<?php

use App\Application\Project\Enums\AssignmentStatus;
use App\Application\Project\Enums\AssignmentType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->foreignId('assigned_to')->nullable()->constrained('educators')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('assigned_by')->nullable()->constrained('admins')->cascadeOnUpdate()->nullOnDelete();
            $table->string('assigned_type', 50)->default(AssignmentType::defaultStatus());
            $table->foreignId('bid_id')->nullable()->constrained('bids')->nullOnDelete();
            $table->string('status', 50)->default(AssignmentStatus::defaultStatus());
            $table->decimal('amount', 10, 2)->default(0.00);
            $table->text('agreement_notes')->nullable();
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
