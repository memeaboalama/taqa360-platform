<?php

namespace Database\Seeders\Location;

use App\Domain\AppBase\Models\Location\Distract;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DistractSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('distracts')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        Distract::create([
            'title' =>  [
                'ar'    =>  'حي الياسمين',
                'en'    =>  'Yasmine',
            ],
            'key'=>'حي الياسمين',
            'city_id'    =>  1,
            'country_id'    =>  1,
        ]);
    }
}
