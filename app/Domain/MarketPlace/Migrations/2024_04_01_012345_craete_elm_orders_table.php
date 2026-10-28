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
        Schema::create('elm_orders', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('order_id')->nullable()->constrained('merchant_orders','id')->nullOnDelete();
            $table->string('elm_id' ,255)->nullable()->unique(); 
            $table->string('authorityId' ,255)->default('NV25GlPuOnQ='); 
            $table->string('categoryId' ,255)->default('NV25GlPuOnQ='); 
            $table->date('deliveryTime')->nullable();
            $table->date('acceptanceDateTime')->nullable();
            $table->enum('elm_upload_status',[0,1,2,3,4,5,6,7,10])->default(1);
            $table->json('elm_errors')->nullable();
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
        Schema::dropIfExists('elm_orders');
    }
};
