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
        Schema::table('cities', function (Blueprint $table) {
            $table->string("elm_id" ,255)->nullable()->unique()->after('id');
            $table->foreignId('region_id')->after('country_id')->nullable()->constrained('regions', 'id')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->dropColumn('elm_id');
            $table->dropForeign('cities_region_id_foreign');
            $table->dropColumn('region_id');
        });
    }
};
