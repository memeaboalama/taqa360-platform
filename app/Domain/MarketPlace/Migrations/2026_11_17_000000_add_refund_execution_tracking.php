<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('refund_payments', 'status')) {
            Schema::table('refund_payments', function (Blueprint $table) {
                $table->string('status', 20)->default('executed');
            });
        }
        if (!Schema::hasColumn('refund_payments', 'refund_method')) {
            Schema::table('refund_payments', function (Blueprint $table) {
                $table->string('refund_method', 30)->default('wallet');
            });
        }
        if (!Schema::hasColumn('refund_payments', 'collection_payment_record_id')) {
            Schema::table('refund_payments', function (Blueprint $table) {
                $table->foreignId('collection_payment_record_id')
                    ->nullable()
                    ->constrained('payments')
                    ->nullOnDelete();
            });
        }
        if (!Schema::hasColumn('refund_payments', 'settlement_id')) {
            Schema::table('refund_payments', function (Blueprint $table) {
                $table->foreignId('settlement_id')
                    ->nullable()
                    ->constrained('order_settlements')
                    ->nullOnDelete();
            });
        }
        if (!Schema::hasColumn('refund_payments', 'requested_by')) {
            Schema::table('refund_payments', function (Blueprint $table) {
                $table->foreignId('requested_by')->nullable()->constrained('admins')->nullOnDelete();
            });
        }
        if (!Schema::hasColumn('refund_payments', 'requested_at')) {
            Schema::table('refund_payments', fn (Blueprint $table) => $table->timestamp('requested_at')->nullable());
        }
        if (!Schema::hasColumn('refund_payments', 'executed_by')) {
            Schema::table('refund_payments', function (Blueprint $table) {
                $table->foreignId('executed_by')->nullable()->constrained('admins')->nullOnDelete();
            });
        }
        if (!Schema::hasColumn('refund_payments', 'executed_at')) {
            Schema::table('refund_payments', fn (Blueprint $table) => $table->timestamp('executed_at')->nullable());
        }
        if (!Schema::hasColumn('refund_payments', 'reference')) {
            Schema::table('refund_payments', fn (Blueprint $table) => $table->string('reference')->nullable());
        }
        if (!Schema::hasColumn('refund_payments', 'proof_path')) {
            Schema::table('refund_payments', fn (Blueprint $table) => $table->string('proof_path')->nullable());
        }
    }

    public function down(): void
    {
        $columns = [
            'proof_path',
            'reference',
            'executed_at',
            'executed_by',
            'requested_at',
            'requested_by',
            'settlement_id',
            'collection_payment_record_id',
            'refund_method',
            'status',
        ];
        foreach (array_reverse($columns) as $column) {
            if (Schema::hasColumn('refund_payments', $column)) {
                Schema::table('refund_payments', function (Blueprint $table) use ($column) {
                    if (in_array($column, ['executed_by', 'requested_by', 'settlement_id', 'collection_payment_record_id'], true)) {
                        $table->dropConstrainedForeignId($column);
                    } else {
                        $table->dropColumn($column);
                    }
                });
            }
        }
    }
};
