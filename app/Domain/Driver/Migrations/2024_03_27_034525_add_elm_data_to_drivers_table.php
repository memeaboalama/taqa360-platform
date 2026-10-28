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
            $table->string("elm_id" ,255)->after('id')->nullable()->unique(); 
            $table->enum('IdentityTypeId',['NV25GlPuOnQ=','oIcaYzeDfQQ='])->after('status')->nullable();
            $table->date('dateOfBirth')->nullable()->after('Id_expire_date');
            $table->foreignId('region_id')->after('country_name')->nullable()->constrained('regions', 'id')->nullOnDelete();
            $table->foreignId('car_type_id')->after('dateOfBirth')->nullable()->constrained('cars_types', 'id')->nullOnDelete();
            $table->integer('carNumber')->after('car_type_id')->nullable();
            $table->string('vehicleSequenceNumber')->after('carNumber')->nullable();
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
            $table->dropColumn('elm_id');
            $table->dropColumn('IdentityTypeId');
            $table->dropColumn('dateOfBirth');
            // $table->dropForeign(['region_id']);
            $table->dropColumn('region_id');
            // $table->dropForeign(['car_type_id']);
            $table->dropColumn('car_type_id');
            $table->dropColumn('carNumber');
            $table->dropColumn('vehicleSequenceNumber');
        });
    }

};
