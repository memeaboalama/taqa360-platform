<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('merchant_ibans')) {
            Schema::table('merchant_ibans', function (Blueprint $table) {
                if (!Schema::hasColumn('merchant_ibans', 'is_active')) {
                    $table->boolean('is_active')->default(true)->after('is_temp');
                }
                if (!Schema::hasColumn('merchant_ibans', 'approved_at')) {
                    $table->timestamp('approved_at')->nullable()->after('is_active');
                }
                if (!Schema::hasColumn('merchant_ibans', 'approved_by')) {
                    $table->unsignedBigInteger('approved_by')->nullable()->after('approved_at');
                }
                if (!Schema::hasColumn('merchant_ibans', 'superseded_at')) {
                    $table->timestamp('superseded_at')->nullable()->after('approved_by');
                }
                if (!Schema::hasColumn('merchant_ibans', 'request_id')) {
                    $table->unsignedBigInteger('request_id')->nullable()->after('superseded_at');
                }
                if (!Schema::hasColumn('merchant_ibans', 'iban_file_disk')) {
                    $table->string('iban_file_disk', 32)->nullable()->after('iban_file');
                }
            });

            // Existing approved rows (is_temp = 0) are active; existing temp rows (is_temp = 1) are not active.
            DB::table('merchant_ibans')
                ->where('is_temp', 0)
                ->whereNull('approved_at')
                ->update([
                    'is_active' => true,
                    'approved_at' => DB::raw('COALESCE(updated_at, created_at, NOW())'),
                ]);

            DB::table('merchant_ibans')
                ->where('is_temp', 1)
                ->update(['is_active' => false]);
        }

        if (!Schema::hasTable('merchant_bank_account_requests')) {
            Schema::create('merchant_bank_account_requests', function (Blueprint $table) {
                $table->id();
                $table->string('request_number', 32)->unique();
                $table->foreignId('merchant_id')->constrained('merchants')->cascadeOnUpdate();
                $table->unsignedBigInteger('previous_iban_id')->nullable()->index();
                $table->unsignedBigInteger('parent_request_id')->nullable()->index();
                $table->string('bank_name');
                $table->string('beneficiary_full_name');
                $table->string('iban', 34)->index();
                $table->string('iban_file')->nullable();
                $table->string('iban_file_disk', 32)->default('local');
                $table->string('iban_file_mime', 100)->nullable();
                $table->text('change_reason')->nullable();
                $table->enum('status', ['draft', 'pending_review', 'approved', 'rejected', 'cancelled'])
                    ->default('pending_review')
                    ->index();
                $table->enum('submitted_by_type', ['merchant', 'admin'])->default('merchant');
                $table->unsignedBigInteger('submitted_by_id')->nullable();
                $table->timestamp('submitted_at')->nullable();
                $table->unsignedBigInteger('reviewed_by_admin_id')->nullable();
                $table->timestamp('reviewed_at')->nullable();
                $table->text('rejection_reason')->nullable();
                $table->boolean('document_verified')->default(false);
                $table->timestamps();

                $table->index(['merchant_id', 'status'], 'idx_mbar_merchant_status');
            });
        }

        if (!Schema::hasTable('merchant_bank_account_logs')) {
            Schema::create('merchant_bank_account_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('request_id')
                    ->constrained('merchant_bank_account_requests')
                    ->cascadeOnDelete();
                $table->foreignId('merchant_id')->constrained('merchants')->cascadeOnUpdate();
                $table->enum('actor_type', ['merchant', 'admin', 'system'])->default('merchant');
                $table->unsignedBigInteger('actor_id')->nullable();
                $table->string('action', 40);
                $table->text('notes')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamp('created_at')->useCurrent();

                $table->index(['request_id', 'created_at'], 'idx_mbal_request_created');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('merchant_bank_account_logs');
        Schema::dropIfExists('merchant_bank_account_requests');

        if (Schema::hasTable('merchant_ibans')) {
            Schema::table('merchant_ibans', function (Blueprint $table) {
                foreach (['iban_file_disk', 'request_id', 'superseded_at', 'approved_by', 'approved_at', 'is_active'] as $col) {
                    if (Schema::hasColumn('merchant_ibans', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
