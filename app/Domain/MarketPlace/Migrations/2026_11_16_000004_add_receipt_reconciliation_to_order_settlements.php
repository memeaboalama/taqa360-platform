<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('order_settlements', 'received_amount')) {
            Schema::table('order_settlements', fn (Blueprint $table) => $table->decimal('received_amount', 10, 2)->nullable());
        }
        if (!Schema::hasColumn('order_settlements', 'received_at')) {
            Schema::table('order_settlements', fn (Blueprint $table) => $table->timestamp('received_at')->nullable());
        }
        if (!Schema::hasColumn('order_settlements', 'received_by')) {
            Schema::table('order_settlements', fn (Blueprint $table) => $table->string('received_by')->nullable());
        }
        if (!Schema::hasColumn('order_settlements', 'receipt_confirmed')) {
            Schema::table('order_settlements', fn (Blueprint $table) => $table->boolean('receipt_confirmed')->default(false));
        }

        Schema::table('order_settlements', fn (Blueprint $table) => $table->dropForeign(['order_id']));
        Schema::table('order_settlements', fn (Blueprint $table) => $table->dropUnique('order_settlements_order_id_unique'));
        Schema::table('order_settlements', function (Blueprint $table) {
            $table->foreign('order_id')->references('id')->on('merchant_orders')->cascadeOnDelete();
        });

        if (!Schema::hasColumn('payments', 'settlement_id')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->foreignId('settlement_id')
                    ->nullable()
                    ->constrained('order_settlements')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (\Illuminate\Support\Facades\DB::table('order_settlements')
            ->select('order_id')
            ->groupBy('order_id')
            ->havingRaw('COUNT(*) > 1')
            ->exists()) {
            throw new \RuntimeException('Cannot restore one-settlement-per-order while rejected settlement history exists.');
        }

        Schema::table('payments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('settlement_id');
        });

        Schema::table('order_settlements', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->unique('order_id');
            $table->foreign('order_id')->references('id')->on('merchant_orders')->cascadeOnDelete();
            $table->dropColumn(['received_amount', 'received_at', 'received_by', 'receipt_confirmed']);
        });
    }
};
