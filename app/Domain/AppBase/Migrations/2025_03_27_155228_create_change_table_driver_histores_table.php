<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::statement("ALTER TABLE driver_history 
        MODIFY COLUMN action ENUM('Enter', 'Exit', 'NetworkStatus0', 'NetworkStatus1')
         DEFAULT 'Enter'");
    }

    public function down()
    {
        Schema::table('driver_history', function (Blueprint $table) {
            $table->enum('action', ['Enter', 'Exit', 'Enable', 'Disable'])
                  ->default('Enter')
                  ->change();
        });
    }
};
