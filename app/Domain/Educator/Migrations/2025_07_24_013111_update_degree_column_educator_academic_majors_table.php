<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('educator_academic_majors', 'degree')) {
            Schema::table('educator_academic_majors', function (Blueprint $table) {
                $table->dropColumn('degree');
            });
        }

        Schema::table('educator_academic_majors', function (Blueprint $table) {
            $table->string('degree')->default('bachelor')->after('graduation_year');
        });
    }

    public function down(): void
    {
        Schema::table('educator_academic_majors', function (Blueprint $table) {
            $table->dropColumn('degree');
        });

        Schema::table('educator_academic_majors', function (Blueprint $table) {
            $table->enum('degree', ['diploma', 'bachelor', 'master', 'phd'])->default('bachelor');
        });
    }
};
