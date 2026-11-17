<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use RuntimeException;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('notification_news', 'body')) {
            return;
        }

        Schema::table('notification_news', function (Blueprint $table): void {
            $table->string('body', 500)->change();
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('notification_news', 'body')) {
            return;
        }

        $lengthFunction = DB::connection()->getDriverName() === 'sqlite' ? 'LENGTH' : 'CHAR_LENGTH';
        if (DB::table('notification_news')->whereRaw("{$lengthFunction}(body) > ?", [120])->exists()) {
            throw new RuntimeException('Cannot reduce notification body length while messages exceed 120 characters.');
        }

        Schema::table('notification_news', function (Blueprint $table): void {
            $table->string('body', 120)->change();
        });
    }
};
