<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchant_branches_work_day_hours', function (Blueprint $table) {
           $table->dropForeign(['merchant_branch_id']);
            $table->foreignId('merchant_branch_id')->nullable()->change();
            $table->foreign('merchant_branch_id')
                ->references('id')
                ->on('merchant_branches')
                ->cascadeOnUpdate()->nullOnDelete();

        });

        Schema::table('merchant_branches_work_days', function (Blueprint $table) {
            $table->dropForeign(['merchant_branch_id']);
            $table->foreignId('merchant_branch_id')->nullable()->change();
            $table->foreign('merchant_branch_id')
                ->references('id')
                ->on('merchant_branches')
                ->cascadeOnUpdate()->nullOnDelete();

        });

        Schema::table('merchant_branches', function (Blueprint $table) {
            $table->dropForeign(['merchant_id']);
            $table->foreignId('merchant_id')->nullable()->change();
            $table->foreign('merchant_id')
                ->references('id')
                ->on('merchants')
                ->cascadeOnUpdate()->nullOnDelete();

        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
};
