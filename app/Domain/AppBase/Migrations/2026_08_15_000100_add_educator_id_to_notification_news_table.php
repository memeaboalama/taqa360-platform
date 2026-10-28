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
        Schema::table('notification_news', function (Blueprint $table) {
            if (! Schema::hasColumn('notification_news', 'educator_id')) {
                $table->unsignedBigInteger('educator_id')->nullable()->index()->after('device_type');
            }
            if (! Schema::hasColumn('notification_news', 'is_read')) {
                $table->boolean('is_read')->default(false)->after('educator_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notification_news', function (Blueprint $table) {
            if (Schema::hasColumn('notification_news', 'is_read')) {
                $table->dropColumn('is_read');
            }
            if (Schema::hasColumn('notification_news', 'educator_id')) {
                $table->dropColumn('educator_id');
            }
        });
    }
};