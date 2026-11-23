<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $columns = ['deliverables', 'exclusions', 'requirements', 'duration', 'pricing_mode', 'price'];

    public function up(): void
    {
        if (! Schema::hasTable('services')) {
            return;
        }
        $missing = array_filter($this->columns, fn ($column) => ! Schema::hasColumn('services', $column));
        Schema::table('services', function (Blueprint $table) use ($missing) {
            foreach ($missing as $column) {
                if ($column === 'pricing_mode') {
                    $table->string($column, 20)->default('quote');
                } elseif ($column === 'price') {
                    $table->decimal($column, 12, 2)->nullable();
                } else {
                    $table->json($column)->nullable();
                }
            }
        });
    }

    public function down(): void
    {
        $columns = array_filter($this->columns, fn ($column) => Schema::hasColumn('services', $column));
        if ($columns) {
            Schema::table('services', fn (Blueprint $table) => $table->dropColumn($columns));
        }
    }
};
