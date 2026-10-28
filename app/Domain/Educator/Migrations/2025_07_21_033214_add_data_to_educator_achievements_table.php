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

            if (Schema::hasColumn('educator_achievements', 'date')) {
                $table->dropColumn('date');
            }

            $table->string('type')->default('work_experience')->after('title');
            $table->date('start_date')->nullable()->after('organization');
            $table->date('end_date')->nullable()->after('start_date');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('educator_achievements', function (Blueprint $table) {
            $table->date('date')->after('title');
        });

        Schema::table('educator_achievements', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('end_date');
            $table->dropColumn('start_date');
        });
    }
};
