<?php

namespace Database\Seeders\Towing;

use App\Domain\Towing\Models\TowingType;
use App\Support\Enum\TowingTypeEnum as TowingTypeEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TowingTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('towing_types')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        TowingType::create([
            'title' =>  [
                'ar'    =>  'عادية',
                'en'    =>  'Normal',
            ],
            'slug'  =>  TowingTypeEnum::NORMAL
        ]);

        TowingType::create([
            'title' =>  [
                'ar'    =>  'هيدروليك',
                'en'    =>  'Hydraulic',
            ],
            'slug'  =>  TowingTypeEnum::HYDRAULIC

        ]);
    }
}
