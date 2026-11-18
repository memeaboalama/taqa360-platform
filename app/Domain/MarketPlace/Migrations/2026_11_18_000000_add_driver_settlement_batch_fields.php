<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_settlements', function (Blueprint $table) {
            $table->unsignedBigInteger('batch_id')->nullable()->index();
            $table->date('supply_date')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('order_settlements', function (Blueprint $table) {
            $table->dropIndex(['batch_id']);
            $table->dropColumn(['batch_id', 'supply_date']);
        });
    }
};
