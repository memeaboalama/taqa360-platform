<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('educator_profiles', function (Blueprint $table) {
            $table->boolean('is_email_public')->default(false);
            $table->boolean('is_phone_public')->default(false);
            $table->boolean('is_identity_id_public')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('educator_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'is_email_public',
                'is_phone_public',
                'is_identity_id_public',
            ]);
        });
    }
};
