<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{

    public function up(): void
    {
        DB::statement("ALTER TABLE ad_placements MODIFY COLUMN type VARCHAR(255) NOT NULL");
        DB::statement("ALTER TABLE ad_placements MODIFY COLUMN status VARCHAR(255) NOT NULL DEFAULT 'active'");

        DB::statement("ALTER TABLE ad_banners MODIFY COLUMN status VARCHAR(255) NOT NULL DEFAULT 'active'");

        DB::statement("ALTER TABLE ad_pages MODIFY COLUMN status VARCHAR(255) NOT NULL DEFAULT 'active'");
    }

    public function down(): void
    {

    }
};
