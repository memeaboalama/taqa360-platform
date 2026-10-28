<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->foreignId('main_category_id')->nullable()->after('category_id');
            $table->foreign('main_category_id')->references('id')->on('categories')->onDelete('cascade');

            $table->foreignId('sub_category_id')->nullable()->after('main_category_id');
            $table->foreign('sub_category_id')->references('id')->on('sub_categories')->onDelete('cascade');

            $table->foreignId('child_category_id')->nullable()->after('sub_category_id');
            $table->foreign('child_category_id')->references('id')->on('child_categories')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign(['main_category_id']);
            $table->dropColumn('main_category_id');

            $table->dropForeign(['sub_category_id']);
            $table->dropColumn('sub_category_id');

            $table->dropForeign(['child_category_id']);
            $table->dropColumn('child_category_id');
        });
    }
};
