<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tasks') && ! Schema::hasColumn('tasks', 'completed_at')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->timestamp('completed_at')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('tasks') && Schema::hasColumn('tasks', 'completed_at')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->dropColumn('completed_at');
            });
        }
    }
};
