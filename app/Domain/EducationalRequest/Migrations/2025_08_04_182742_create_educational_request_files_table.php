<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('educational_request_files', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->morphs('fileable');
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
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('educational_request_files');
    }
};
