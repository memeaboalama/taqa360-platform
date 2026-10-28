<?php

namespace Database\Seeders\Towing;

use App\Domain\Towing\Models\TowingCity;
use App\Support\Enum\StatusEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use MatanYadaev\EloquentSpatial\Objects\Point;

class TowingCitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('towing_cities')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        TowingCity::create([
            'title' =>  [
                'ar'    =>  'الرياض',
                'en'    =>  'Riyadh',
            ],
            'location' => new Point(24.7254569,47.151498,4326),
            'priority' => 1,
            'can_pickup' => StatusEnum::ACTIVE,
            'can_dropoff' => StatusEnum::ACTIVE,
        ]);


        TowingCity::create([
            'title' =>  [
                'ar'    =>  'جدة',
                'en'    =>  'Jeddah',
            ],
            'location' => new Point(21.485811,39.1925048,4326),
            'priority' => 2,
            'can_pickup' => StatusEnum::ACTIVE,
            'can_dropoff' => StatusEnum::ACTIVE,
        ]);

    }
}
