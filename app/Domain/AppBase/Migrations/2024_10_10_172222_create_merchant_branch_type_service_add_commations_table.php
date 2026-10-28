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
        Schema::table('merchant_branch_type_services', function (Blueprint $table) {
            $table->double('discount_rate',8,2)->after('package_id')->nullable();
            $table->double('commission',8,2)->after('package_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchant_branch_type_services');
    }
};
