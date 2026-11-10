<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('educator_profiles', function (Blueprint $table) {
            $table->enum('gender', ['male', 'female'])->nullable()->change();
            $table->date('birth_date')->nullable()->change();
        });
    }

    public function down(): void
    {
        if (DB::table('educator_profiles')->whereNull('gender')->orWhereNull('birth_date')->exists()) {
            throw new RuntimeException('Cannot make educator profile demographics required while null values exist.');
        }

        Schema::table('educator_profiles', function (Blueprint $table) {
            $table->enum('gender', ['male', 'female'])->nullable(false)->change();
            $table->date('birth_date')->nullable(false)->change();
        });
    }
};
