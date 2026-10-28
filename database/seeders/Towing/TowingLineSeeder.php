<?php

namespace Database\Seeders\Towing;

use App\Domain\Towing\Models\TowingLine;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TowingLineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('towing_lines')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        TowingLine::create([
            'from_towing_city_id'   =>  1,
            'to_towing_city_id' => 2,
            'towing_type_id' => 1,
            'price' =>  200,
        ]);


        TowingLine::create([
            'from_towing_city_id'   =>  1,
            'to_towing_city_id' => 2,
            'towing_type_id' => 2,
            'price' =>  200,
        ]);
    }
}
