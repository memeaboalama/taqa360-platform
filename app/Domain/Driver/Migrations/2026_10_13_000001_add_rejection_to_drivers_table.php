<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add driver rejection support: extend the status enum and store the reason.
     * Additive only — no existing data is touched.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE `drivers` MODIFY `status` ENUM('active','inactive','rejected') NOT NULL DEFAULT 'active'");

        Schema::table('drivers', function (Blueprint $table) {
            $table->text('rejection_reason')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Map rejected rows back before shrinking the enum so no data is lost.
        DB::table('drivers')->where('status', 'rejected')->update(['status' => 'inactive', 'rejection_reason' => null]);

        Schema::table('drivers', function (Blueprint $table) {
            $table->dropColumn('rejection_reason');
        });

        DB::statement("ALTER TABLE `drivers` MODIFY `status` ENUM('active','inactive') NOT NULL DEFAULT 'active'");
    }
};
