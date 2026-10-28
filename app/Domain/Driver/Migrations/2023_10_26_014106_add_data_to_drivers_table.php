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
        Schema::table('drivers', function (Blueprint $table) {
            $table->string('Id_number')->nullable()->after('status');
            $table->date('Id_expire_date')->nullable()->after('Id_number');
            $table->boolean('is_complete')->default(false)->after('Id_expire_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropColumn('Id_number');
            $table->dropColumn('Id_expire_date');
            $table->dropColumn('is_complete');
        });
    }
};
