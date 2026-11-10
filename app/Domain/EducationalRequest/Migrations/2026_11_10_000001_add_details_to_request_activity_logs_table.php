<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('request_activity_logs') && ! Schema::hasColumn('request_activity_logs', 'details')) {
            Schema::table('request_activity_logs', function (Blueprint $table) {
                $table->text('details')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('request_activity_logs') && Schema::hasColumn('request_activity_logs', 'details')) {
            Schema::table('request_activity_logs', function (Blueprint $table) {
                $table->dropColumn('details');
            });
        }
    }
};
