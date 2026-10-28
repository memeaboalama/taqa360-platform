<?php

namespace Database\Seeders\Car;

use App\Domain\AppBase\Models\Car\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('brands')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        Brand::create([
            'title' =>  [
                'ar'    =>  'بى ام دابيليو',
                'en'    =>  'BMW',
            ],
        ]);
    }
}
