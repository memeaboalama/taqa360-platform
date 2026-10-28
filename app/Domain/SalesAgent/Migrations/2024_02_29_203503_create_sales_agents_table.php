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
        // Schema::table('sales_agent_bank_info', function (Blueprint $table) {
        //     $table->dropForeign(['sales_agent_id']);
        //     // $table->dropForeign(['sales_agent_id']);
        // });
        // Schema::table('promocodes', function (Blueprint $table) {
        //     $table->dropForeign(['sales_agent_id']);
        //     // $table->dropForeign(['sales_agent_id']);
        // });
        // Schema::dropIfExists('sales_agents');
        
        Schema::create('sales_agents', function (Blueprint $table) {
            $table->id('id');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('password')->nullable();
            $table->string('dialcode',3)->default('966');
            $table->string('country_name',50)->default('Saudi Arabia ,(المملكة العربية السعودية)');
            $table->integer('country_id')->nullable();
            $table->string('country_code',3)->default('sa');
            $table->integer('city_id')->nullable();
            $table->integer('district_id')->nullable();
            $table->timestamp('last_logged_in_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->enum('status',['active','inactive'])->default('active');
            $table->string('Id_number')->nullable();
            $table->date('Id_expire_date')->nullable();
            $table->float('taken_commission')->default(20.00);
            $table->string('iban')->nullable();
            $table->boolean('is_complete')->default(1);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_agents');
    }
};
