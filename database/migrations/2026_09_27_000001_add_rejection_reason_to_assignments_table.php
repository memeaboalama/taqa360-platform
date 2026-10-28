<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The admin "edit assignment" modal has two text inputs (a reason and a message
 * to the specialist) but the table only had a single `agreement_notes` column,
 * so whichever field was not submitted was silently lost and the other one came
 * back filled. Give the rejection reason its own column.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('assignments', 'rejection_reason')) {
            return;
        }

        Schema::table('assignments', function (Blueprint $table) {
            $table->text('rejection_reason')->nullable()->after('agreement_notes');
        });
    }

    public function down(): void
    {
        if (!Schema::hasColumn('assignments', 'rejection_reason')) {
            return;
        }

        Schema::table('assignments', function (Blueprint $table) {
            $table->dropColumn('rejection_reason');
        });
    }
};
