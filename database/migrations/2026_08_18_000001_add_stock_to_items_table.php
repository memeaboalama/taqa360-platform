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
        if (!Schema::hasColumn('items', 'stock')) {
            Schema::table('items', function (Blueprint $table) {
                $table->integer('stock')->nullable()->default(null)->after('default_price');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('items', 'stock')) {
            Schema::table('items', function (Blueprint $table) {
                $table->dropColumn('stock');
            });
        }
    }
};
