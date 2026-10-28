<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('educator_profiles', function (Blueprint $table) {
            $table->string('identity_status')->default('pending')->after('identity_type');
        });
    }

    public function down(): void
    {
        Schema::table('educator_profiles', function (Blueprint $table) {
            $table->dropColumn('identity_status');
        });
    }
};
