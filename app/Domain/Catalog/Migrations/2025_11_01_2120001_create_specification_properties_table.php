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
        // DB::transaction(function () {
            Schema::create('specification_properties', function (Blueprint $table) {
                $table->id();
                $table->foreignId('specification_id')->nullable()->constrained('specifications', 'id')->cascadeOnDelete()->cascadeOnUpdate();
                $table->foreignId('item_id')->nullable()->constrained('items', 'id')->nullOnDelete();
                $table->string(column: 'code')->nullable()->index();
                $table->text(column: 'name');
                $table->longText(column: 'value')->nullable();
                $table->string('status')->nullable()->default('active');
                $table->json('meta')->nullable();
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrentOnUpdate();
                // $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->nullable();
                // $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->nullable();
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
            Schema::dropIfExists('item_specification_properties');
            // DB::commit();
        // });
    }
};
