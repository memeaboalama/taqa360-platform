<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::table('educator_profiles', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->unique();
        });

        Schema::table('educator_academic_majors', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->unique();
        });

        Schema::table('educator_languages', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->unique();
        });

        Schema::table('educator_services', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->unique();
        });

        Schema::table('educator_achievements', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->unique();
        });
        Schema::table('educator_bios', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->unique();
        });
        Schema::table('educator_social_links', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->unique();
        });

        Schema::table('educator_skills', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->unique();
        });
    }

    public function down(): void
    {
        Schema::table('educator_profiles', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });

        Schema::table('educator_academic_majors', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });

        Schema::table('educator_languages', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });

        Schema::table('educator_services', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });

        Schema::table('educator_achievements', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });

        Schema::table('educator_bios', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });

        Schema::table('educator_social_links', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });

        Schema::table('educator_skills', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
}
};
