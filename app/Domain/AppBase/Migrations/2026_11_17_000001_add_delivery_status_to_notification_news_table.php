<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notification_news', function (Blueprint $table) {
            if (! Schema::hasColumn('notification_news', 'delivery_status')) {
                $table->string('delivery_status', 32)->default('unknown');
            }
            if (! Schema::hasColumn('notification_news', 'sent_count')) {
                $table->unsignedInteger('sent_count')->default(0);
            }
            if (! Schema::hasColumn('notification_news', 'failed_count')) {
                $table->unsignedInteger('failed_count')->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::table('notification_news', function (Blueprint $table) {
            if (Schema::hasColumn('notification_news', 'failed_count')) {
                $table->dropColumn('failed_count');
            }
            if (Schema::hasColumn('notification_news', 'sent_count')) {
                $table->dropColumn('sent_count');
            }
            if (Schema::hasColumn('notification_news', 'delivery_status')) {
                $table->dropColumn('delivery_status');
            }
        });
    }
};
