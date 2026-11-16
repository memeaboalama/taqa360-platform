<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('driver_collection_failure_reports')) {
            Schema::create('driver_collection_failure_reports', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained('merchant_orders')->cascadeOnDelete();
                $table->foreignId('driver_id')->constrained('drivers')->cascadeOnDelete();
                $table->text('reason');
                $table->text('driver_note')->nullable();
                $table->string('status', 30)->default('open');
                $table->text('admin_instructions')->nullable();
                $table->foreignId('decided_by')->nullable()->constrained('admins')->nullOnDelete();
                $table->timestamp('decided_at')->nullable();
                $table->timestamp('resolved_at')->nullable();
                $table->timestamps();
                $table->index(['status', 'created_at']);
                $table->index(['driver_id', 'status']);
            });
        }

        if (!Schema::hasTable('driver_collection_failure_report_histories')) {
            Schema::create('driver_collection_failure_report_histories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('report_id')->constrained('driver_collection_failure_reports')->cascadeOnDelete();
                $table->string('action', 40);
                $table->string('status_from', 30)->nullable();
                $table->string('status_to', 30);
                $table->text('note')->nullable();
                $table->string('actor_type')->nullable();
                $table->unsignedBigInteger('actor_id')->nullable();
                $table->timestamp('created_at');
                $table->index(['report_id', 'created_at'], 'dcfrh_report_created_idx');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('driver_collection_failure_report_histories');
        Schema::dropIfExists('driver_collection_failure_reports');
    }
};
