
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (config('app.brand') !== 'taqa360'
            || ! Schema::hasTable('educational_requests')
            || ! Schema::hasTable('services')
            || Schema::hasColumn('educational_requests', 'service_id')) {
            return;
        }

        Schema::table('educational_requests', function (Blueprint $table) {
            $table->foreignId('service_id')
                ->nullable()
                ->constrained('services')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        if (config('app.brand') !== 'taqa360'
            || ! Schema::hasTable('educational_requests')
            || ! Schema::hasColumn('educational_requests', 'service_id')) {
            return;
        }

        Schema::table('educational_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('service_id');
        });
    }
};
