<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       
        Schema::table('item_prices', function (Blueprint $table) {
            $table->foreign('type_service_id')->references('id')->on('type_services');
            $table->foreign('item_id')->references('id')->on('items');
            // $table->timestamps();
        });
        Schema::table('merchant_branch_type_services', function (Blueprint $table) {
            $table->foreign('type_service_id')->references('id')->on('type_services');
            $table->foreign('merchant_branch_id')->references('id')->on('merchant_branches');
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations
     */
    public function down(): void
    {
        // Schema::dropIfExists('edit_type_childrens');
    }
};
