<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('refund_payments', function (Blueprint $table) {
            $table->string('funded_by')->default('platform')->after('calculated_on');
            $table->unsignedBigInteger('driver_id')->nullable()->after('funded_by')->index();
        });
    }

    public function down(): void
    {
        Schema::table('refund_payments', function (Blueprint $table) {
            $table->dropIndex(['driver_id']);
            $table->dropColumn(['funded_by', 'driver_id']);
        });
    }
};
