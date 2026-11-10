<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('educational_requests')) {
            return;
        }

        if (! Schema::hasColumn('educational_requests', 'project_site_type')) {
            Schema::table('educational_requests', function (Blueprint $table) {
                $table->string('project_site_type')->nullable();
            });
        }

        if (! Schema::hasColumn('educational_requests', 'solar_system_type')) {
            Schema::table('educational_requests', function (Blueprint $table) {
                $table->string('solar_system_type')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('educational_requests')) {
            return;
        }

        $columns = array_values(array_filter(
            ['project_site_type', 'solar_system_type'],
            fn (string $column) => Schema::hasColumn('educational_requests', $column)
        ));

        if ($columns) {
            Schema::table('educational_requests', function (Blueprint $table) use ($columns) {
                $table->dropColumn($columns);
            });
        }
    }
};
