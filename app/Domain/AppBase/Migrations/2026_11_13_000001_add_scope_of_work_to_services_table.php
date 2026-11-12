<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('services') && ! Schema::hasColumn('services', 'scope_of_work')) {
            Schema::table('services', function (Blueprint $table) {
                $table->text('scope_of_work')->nullable()->after('description');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('services') && Schema::hasColumn('services', 'scope_of_work')) {
            Schema::table('services', function (Blueprint $table) {
                $table->dropColumn('scope_of_work');
            });
        }
    }
};
