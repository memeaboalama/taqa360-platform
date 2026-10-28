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
      Schema::table('merchant_branches', function (Blueprint $table) {
        $table->dropForeign('merchant_branches_distract_id_foreign'); // Drop the existing constraint
        // $table->dropForeign('merchant_branches_distract_id_index'); // Drop the existing constraint
        // $table->dropForeign(['distracts_distract_id']); // Drop the existing constraint
        $table->dropColumn(['distract_id']); // Drop the existing constraint
        });
        Schema::table('merchant_branches', function (Blueprint $table) {
            $table->foreignId('distract_id')
              ->after('city_id') 
              ->nullable()
              // ->change()
              ; 
        
            $table->foreign('distract_id')
              ->references('id')
              ->on('distracts')
              ->nullOnDelete();
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
