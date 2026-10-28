<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchant_services_category', function (Blueprint $table) {
            $table->foreignId('merchant_branch_id')->after('merchant_id')->nullable();
            $table->foreign('merchant_branch_id')->on('merchant_branches')->references('id')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('merchant_services_category', function (Blueprint $table) {
//            $table->dropColumn('password');
        });
    }
};
