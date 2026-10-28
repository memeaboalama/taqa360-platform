<?php

namespace Database\Seeders\Car;

use App\Domain\AppBase\Models\Car\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('models')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        Model::create([
            'title' =>  [
                'ar'    =>  'كومبيتيشن',
                'en'    =>  'competition m1',
            ],
            'brand_id'  =>  1
        ]);
    }
}
