<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('educator_academic_majors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('educator_id')->constrained()->onDelete('cascade');
            $table->foreignId('academic_major_id')->nullable()->constrained('academic_majors')->onDelete('set null');
            $table->string('custom_name')->nullable();
            $table->boolean('is_custom')->default(false);
            $table->string('graduation_institution')->nullable();
            $table->year('graduation_year')->nullable();
            $table->enum('degree', ['diploma', 'bachelor', 'master', 'phd'])->default('bachelor');
            $table->boolean('approval_document')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('educator_academic_majors');
    }
};
