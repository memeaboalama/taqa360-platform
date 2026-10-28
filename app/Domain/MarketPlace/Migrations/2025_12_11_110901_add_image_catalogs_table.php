<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('image')->nullable()->after('description')->nullable();
        });

        Schema::table('sub_categories', function (Blueprint $table) {
            $table->string('image')->nullable()->after('description')->nullable();
        });

        Schema::table('child_categories', function (Blueprint $table) {
            $table->string('image')->nullable()->after('description')->nullable();
        });

    }

    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('image');
        });

        Schema::table('sub_categories', function (Blueprint $table) {
            $table->dropColumn('image');
        });

        Schema::table('child_categories', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
};
