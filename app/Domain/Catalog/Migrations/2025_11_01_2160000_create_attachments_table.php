<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // DB::beginTransaction();
        // DB::transaction(function () {
            Schema::create('attachments', function (Blueprint $table) {
                $table->id();
                $table->string('type')->comment('file or url');
                $table->string('storage')->nullable();
                
                // Paths
                $table->string('path')->nullable()->comment('Path to the original file');
                $table->string('webp_path')->nullable()->comment('Path to the medium-sized WebP version');
                $table->string('thumbnail_path')->nullable()->comment('Path to the small square WebP thumbnail');
                
                // Metadata
                $table->string('title')->nullable();
                $table->text('description')->nullable();
                $table->string('collection_name')->nullable()->comment('For grouping, e.g., "Gallery"');
                
                // File Info
                $table->string('file_name')->nullable();
                $table->string('original_name')->nullable();
                $table->string('mime_type')->nullable();
                
                // URL Info
                $table->string('url', 2048)->nullable();

                $table->json('meta')->nullable();
    
                $table->timestamps();
            });
            // DB::commit();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // DB::transaction(function () {
            Schema::dropIfExists('attachments');
            
            // DB::commit();
        // });
    }
};
