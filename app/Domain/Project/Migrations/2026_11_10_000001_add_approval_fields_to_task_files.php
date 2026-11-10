<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('task_files', 'approved_at') || ! Schema::hasColumn('task_files', 'approved_by')) {
            Schema::table('task_files', function (Blueprint $table) {
                if (! Schema::hasColumn('task_files', 'approved_at')) {
                    $table->timestamp('approved_at')->nullable();
                }
                if (! Schema::hasColumn('task_files', 'approved_by')) {
                    $table->foreignId('approved_by')->nullable()->constrained('admins')->nullOnDelete();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('task_files', 'approved_at') || Schema::hasColumn('task_files', 'approved_by')) {
            Schema::table('task_files', function (Blueprint $table) {
                if (Schema::hasColumn('task_files', 'approved_by')) {
                    $table->dropConstrainedForeignId('approved_by');
                }
                if (Schema::hasColumn('task_files', 'approved_at')) {
                    $table->dropColumn('approved_at');
                }
            });
        }
    }
};
