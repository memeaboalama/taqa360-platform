<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_files', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
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
            $table->timestamp('uploaded_at')->nullable();

            $table->morphs('fileable');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_files');
    }
};
