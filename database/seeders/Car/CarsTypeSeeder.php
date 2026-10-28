<?php

namespace Database\Seeders\Car;

use App\Domain\AppBase\Models\Car\CarsType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarsTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('cars_types')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $types = [
            ['ar' => 'سيدان',        'en' => 'Sedan'],
            ['ar' => 'هاتشباك',      'en' => 'Hatchback'],
            ['ar' => 'دفع رباعي',    'en' => 'SUV'],
            ['ar' => 'بيك أب',       'en' => 'Pickup'],
            ['ar' => 'فان',          'en' => 'Van'],
            ['ar' => 'ميني فان',     'en' => 'Minivan'],
            ['ar' => 'شاحنة',        'en' => 'Truck'],
            ['ar' => 'حافلة',        'en' => 'Bus'],
            ['ar' => 'دراجة نارية',  'en' => 'Motorcycle'],
        ];

        foreach ($types as $type) {
            CarsType::create([
                'title' => $type,
                'key'   => str()->slug($type['en']),
            ]);
        }
    }
}
