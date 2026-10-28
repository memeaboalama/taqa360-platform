<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('drivers_registers', function (Blueprint $table) {
            $table->string('vehicleSequenceNumber', 255)->nullable()->after('carNumber');
        });
    }

    public function down(): void
    {
        Schema::table('drivers_registers', function (Blueprint $table) {
            $table->dropColumn('vehicleSequenceNumber');
        });
    }
};