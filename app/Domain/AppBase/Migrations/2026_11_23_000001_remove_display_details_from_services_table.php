<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('services') || ! Schema::hasColumn('services', 'details')) {
            return;
        }

        // Retain the internal catalog identity without keeping display content.
        DB::table('services')->select('id', 'details', 'request_config')->orderBy('id')->chunkById(100, function ($services) {
            foreach ($services as $service) {
                $details = json_decode($service->details ?? '{}', true) ?: [];
                if (empty($details['_seed_key'])) {
                    continue;
                }
                $config = json_decode($service->request_config ?? '{}', true) ?: [];
                $config['_seed_key'] ??= $details['_seed_key'];
                DB::table('services')->where('id', $service->id)->update([
                    'request_config' => json_encode($config, JSON_THROW_ON_ERROR),
                ]);
            }
        });

        Schema::table('services', fn (Blueprint $table) => $table->dropColumn('details'));
    }

    public function down(): void
    {
        if (Schema::hasTable('services') && ! Schema::hasColumn('services', 'details')) {
            Schema::table('services', fn (Blueprint $table) => $table->json('details')->nullable());
        }
    }
};
