<?php

use App\Application\Project\Enums\TaskStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('cascade');
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->json('tags')->nullable();
            $table->string('status')->default(TaskStatus::defaultStatus());
            $table->decimal('budget', 10, 2)->nullable();
            $table->date('deadline');
            $table->boolean('is_public')->default(false);
            $table->enum('bidding_status', ['OPEN', 'CLOSED'])->default('CLOSED');
            $table->foreignId('created_by')->constrained('admins');
            $table->timestamp('modified_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'is_public', 'bidding_status']);
            $table->index(['project_id']);
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
