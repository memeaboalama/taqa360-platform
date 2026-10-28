<?php

namespace Database\Seeders\AppBase;

use App\Domain\AppBase\Models\Setting\Language;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('languages')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        Language::create([
            'title' =>  [
                'ar'    =>  'العربية',
                'en'    =>  'Arabic',
            ],
            'code'  =>  'ar',
        ]);

        Language::create([
            'title' =>  [
                'ar'    =>  'الانجليزية',
                'en'    =>  'English',
            ],
            'code'  =>  'en',
        ]);
    }
}
