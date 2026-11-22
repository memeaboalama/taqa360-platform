<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('services') && ! Schema::hasColumn('services', 'request_config')) {
            Schema::table('services', function (Blueprint $table) {
                $table->json('request_config')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('services') && Schema::hasColumn('services', 'request_config')) {
            Schema::table('services', function (Blueprint $table) {
                $table->dropColumn('request_config');
            });
        }
    }
};
