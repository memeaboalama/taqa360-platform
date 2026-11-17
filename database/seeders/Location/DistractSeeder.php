<?php

namespace Database\Seeders\Location;

use App\Domain\AppBase\Models\Location\Distract;
use Illuminate\Database\Seeder;

class DistractSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Distract::firstOrCreate(
            ['city_id' => 1, 'key' => 'حي الياسمين'],
            [
                'title' => [
                    'ar' => 'حي الياسمين',
                    'en' => 'Yasmine',
                ],
                'country_id' => 1,
            ]
        );
    }
}
