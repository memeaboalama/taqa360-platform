<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('educator_achievements', function (Blueprint $table) {
            if (! Schema::hasColumn('educator_achievements', 'approval_document')) {
                $table->boolean('approval_document')->default(false)->after('description');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('educator_achievements', function (Blueprint $table) {
            if (Schema::hasColumn('educator_achievements', 'approval_document')) {
                $table->dropColumn('approval_document');
            }
        });
    }
};