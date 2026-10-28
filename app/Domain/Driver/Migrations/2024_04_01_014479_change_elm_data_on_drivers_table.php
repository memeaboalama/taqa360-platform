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
            $table->dropColumn('elm_upload_status');
        });
        
        Schema::table('drivers', function (Blueprint $table) { 
            $table->string('carNumber')->after('car_type_id')->nullable()->change();
            $table->enum('elm_upload_status', [0, 1, 2, 3, 4, 10])->default(1)->after('status');
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
