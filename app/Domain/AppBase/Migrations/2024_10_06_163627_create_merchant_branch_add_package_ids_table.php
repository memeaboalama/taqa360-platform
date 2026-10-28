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
        Schema::table('merchant_orders', function (Blueprint $table) {
            // $table->double('commission_amount',8,2)->after('note')->default(null);
            // $table->double('commission_rate',8,2)->after('note')->default(null);
            // $table->bigInteger('package_id')->after('note')->default(null);
            // $table->string('service_type_id',255)->after('note')->default('with_delivery');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchant_branches');
    }
};
