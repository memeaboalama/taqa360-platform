<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('payment_correction_requests', 'original_amount')) {
            Schema::table('payment_correction_requests', function (Blueprint $table) {
                $table->decimal('original_amount', 10, 2)->nullable();
                $table->decimal('corrected_amount', 10, 2)->nullable();
                $table->decimal('adjustment_amount', 10, 2)->nullable();
            });
        }

        if (!Schema::hasColumn('payment_correction_requests', 'settlement_id')) {
            Schema::table('payment_correction_requests', function (Blueprint $table) {
                $table->foreignId('settlement_id')->nullable()->constrained('order_settlements')->nullOnDelete();
            });
        }

        if (!Schema::hasColumn('payment_correction_requests', 'approved_by')) {
            Schema::table('payment_correction_requests', function (Blueprint $table) {
                $table->foreignId('approved_by')->nullable()->constrained('admins')->nullOnDelete();
                $table->timestamp('approved_at')->nullable();
            });
        }

        if (!Schema::hasColumn('payment_correction_requests', 'cancelled_by')) {
            Schema::table('payment_correction_requests', function (Blueprint $table) {
                $table->foreignId('cancelled_by')->nullable()->constrained('admins')->nullOnDelete();
                $table->timestamp('cancelled_at')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('payment_correction_requests', 'cancelled_by')) {
            Schema::table('payment_correction_requests', function (Blueprint $table) {
                $table->dropConstrainedForeignId('cancelled_by');
                $table->dropColumn('cancelled_at');
            });
        }

        if (Schema::hasColumn('payment_correction_requests', 'approved_by')) {
            Schema::table('payment_correction_requests', function (Blueprint $table) {
                $table->dropConstrainedForeignId('approved_by');
                $table->dropColumn('approved_at');
            });
        }

        if (Schema::hasColumn('payment_correction_requests', 'settlement_id')) {
            Schema::table('payment_correction_requests', function (Blueprint $table) {
                $table->dropConstrainedForeignId('settlement_id');
            });
        }

        foreach (['original_amount', 'corrected_amount', 'adjustment_amount'] as $column) {
            if (Schema::hasColumn('payment_correction_requests', $column)) {
                Schema::table('payment_correction_requests', fn (Blueprint $table) => $table->dropColumn($column));
            }
        }
    }
};
