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
        Schema::create('general_settings', function (Blueprint $table) {
            $table->id();
            $table->longText('terms_conditions_en')->nullable();
            $table->longText('terms_conditions_ar')->nullable();
            $table->longText('policies_en')->nullable();
            $table->longText('policies_ar')->nullable();
            $table->longText('auth_page_content_en')->nullable();
            $table->longText('auth_page_content_ar')->nullable();
            $table->string('auth_page_image')->nullable();
            $table->timestamps();
        });

        \App\Domain\AppBase\Models\GeneralSetting::create([
            'terms_conditions_en' => "",
            'terms_conditions_ar' => "",
            'policies_en' => "",
            'policies_ar' => "",
            'auth_page_content_en' => "",
            'auth_page_content_ar' => "",
            'auth_page_image' => "",
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_settings');
    }
};
