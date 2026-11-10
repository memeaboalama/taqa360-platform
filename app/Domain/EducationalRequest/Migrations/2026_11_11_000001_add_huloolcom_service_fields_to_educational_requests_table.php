<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (config('app.brand') !== 'huloolcom' || ! Schema::hasTable('educational_requests')) {
            return;
        }

        if (! Schema::hasColumn('educational_requests', 'academic_major_id')) {
            Schema::table('educational_requests', function (Blueprint $table) {
                $table->foreignId('academic_major_id')
                    ->nullable()
                    ->constrained('academic_majors')
                    ->nullOnDelete();
            });
        }

        if (! Schema::hasColumn('educational_requests', 'education_level')) {
            Schema::table('educational_requests', function (Blueprint $table) {
                $table->string('education_level')->default('Bachelor');
            });
        }
    }

    public function down(): void
    {
        // These fields existed in earlier Huloolcom schemas; dropping them could
        // destroy request data that predates this migration.
    }
};
