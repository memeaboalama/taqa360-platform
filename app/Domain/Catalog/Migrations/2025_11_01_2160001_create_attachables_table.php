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
            Schema::create('attachables', function (Blueprint $table) {
                $table->foreignId('attachment_id')->constrained('attachments')->onDelete('cascade');
                $table->morphs('attachable');

                // Pivot Data for Enhancements
                $table->unsignedInteger('sort_order')->default(0);
                $table->boolean('is_primary')->default(false);

                $table->primary(['attachment_id', 'attachable_id', 'attachable_type']);
                $table->index(['attachable_id', 'attachable_type', 'is_primary']);
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
            Schema::dropIfExists('attachables');
            
            // DB::commit();
        // });
    }
};
