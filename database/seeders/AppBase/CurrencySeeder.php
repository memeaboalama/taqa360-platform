<?php

namespace Database\Seeders\AppBase;

use App\Domain\AppBase\Models\Setting\Currency;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('currencies')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        Currency::create([
            'title' =>  [
                'ar'    =>  'ريال',
                'en'    =>  'Sar',
            ],
            'iso'   =>  'sar'
        ]);
    }
}
