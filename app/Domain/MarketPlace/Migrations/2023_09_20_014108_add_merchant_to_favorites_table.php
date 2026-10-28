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
        Schema::table('favorites', function (Blueprint $table) {
            $table->foreignId('merchant_id')->index()->nullable();
            $table->foreign('merchant_id')->on('merchants')->references('id')->cascadeOnUpdate()->nullOnDelete();

            $table->foreignId('merchant_branch_id')->index()->nullable();
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
        Schema::table('favorites', function (Blueprint $table) {
           // $table->dropColumn('gift_confidential');
        });
    }
};
