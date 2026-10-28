<?php

use App\Application\Project\Enums\BidStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bid_tasks', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('bid_id')->constrained()->onDelete('cascade');
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->string('status')->default(BidStatus::defaultStatus());
            $table->decimal('amount', 10, 2);
            $table->string('rejection_reason')->nullable();
            $table->string('approval_message')->nullable();
            $table->timestamps();

            $table->unique(['bid_id', 'task_id']);
            $table->index(['bid_id']);
            $table->index(['task_id']);
            $table->index(['status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bid_tasks');
    }
};
