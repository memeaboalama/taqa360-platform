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
        Schema::table('general_settings', function (Blueprint $table) {
            $table->string('moyasar_key')->nullable()->after('stc_commission');
            $table->string('moyasar_secret')->nullable()->after('moyasar_key');
            $table->string('moyasar_url')->nullable()->default('https://api.moyasar.com')->after('moyasar_secret');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('general_settings', function (Blueprint $table) {
            $table->dropColumn(['moyasar_key', 'moyasar_secret', 'moyasar_url']);
        });
    }
};
