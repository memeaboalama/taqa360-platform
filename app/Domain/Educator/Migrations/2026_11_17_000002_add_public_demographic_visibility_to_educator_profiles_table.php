<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('educator_profiles', function (Blueprint $table) {
            $table->boolean('is_nationality_public')->default(false);
            $table->boolean('is_gender_public')->default(false);
            $table->boolean('is_country_public')->default(false);
            $table->boolean('is_city_public')->default(false);
            $table->boolean('is_experience_level_public')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('educator_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'is_nationality_public',
                'is_gender_public',
                'is_country_public',
                'is_city_public',
                'is_experience_level_public',
            ]);
        });
    }
};
