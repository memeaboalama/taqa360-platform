<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('educator_academic_majors', function (Blueprint $table) {
            $table->text('rejection_reason')->nullable()->after('approval_document');
        });
    }

    public function down(): void
    {
        Schema::table('educator_academic_majors', function (Blueprint $table) {
            $table->dropColumn('rejection_reason');
        });
    }
};
