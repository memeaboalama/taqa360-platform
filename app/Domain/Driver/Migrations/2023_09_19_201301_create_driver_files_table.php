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
        Schema::create('driver_files', function (Blueprint $table) {
            $table->id();
            $table->string('file_name');
            $table->string('original_name')->nullable();
            $table->string('mime_type')->nullable();
            $table->string('collection_name')->nullable();
            $table->string('type')->nullable();
            $table->string('storage');
            $table->string('url')->nullable();
            $table->string('path')->nullable();
            $table->integer('order_column')->nullable();
            $table->float('size')->nullable();
            $table->morphs('fileable');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_files');
    }
};
