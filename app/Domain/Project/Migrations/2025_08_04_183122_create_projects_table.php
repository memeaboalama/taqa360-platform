<?php

use App\Application\Project\Enums\CreationType;
use App\Application\Project\Enums\ProjectStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('tags')->nullable();
            $table->string('status')->default(ProjectStatus::defaultStatus());
            $table->decimal('budget', 12, 2)->nullable();
            $table->boolean('is_published')->default(false);
            $table->string('creation_type')->default(CreationType::defaultStatus());
            $table->string('created_by');
            $table->date('deadline')->nullable();
            $table->foreignId('educational_request_id')->nullable()->constrained()->onDelete('set null');
            $table->string('requester_name')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'is_published']);
            $table->index(['creation_type']);
            $table->index(['created_by']);
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
