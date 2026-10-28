<?php

namespace Database\Seeders\Car;

use App\Domain\AppBase\Models\Car\Brand;
use App\Domain\AppBase\Models\Car\Model;
use App\Domain\User\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('user_cars')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $data = [
            'brand_id'  =>  Brand::first()->id,
            'model_id'  =>  Model::first()->id,
            'year'  =>  2023,
            'plate_number'  =>  'sa 3211',
        ];
        foreach (User::get() as $user)
        {
            $user->cars()->create($data);
        }
    }
}
