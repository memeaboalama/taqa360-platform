<?php

namespace Database\Seeders\Provider;

use App\Domain\AppBase\Models\Car\Brand;
use App\Domain\AppBase\Models\Car\Model;
use App\Domain\AppBase\Models\Location\Country;
use App\Domain\Provider\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('companies')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $company = Company::create([
            'company_name'  =>  'demo',
            'name'          =>  'demo',
            'email'         =>  'demo@test.com',
            'phone'         =>  '512345678',
            'phone_verified_at' =>  now(),
            'country_id'    =>  Country::first()?->id,
        ]);

        // $company->vehicles()->create([
        //     'model_id'  =>  Model::first()->id,
        //     'brand_id'  =>  Brand::first()->id,
        //     'plate_number'  =>  'dsd 432',
        //     'year'  =>  2020,
        // ]);

    }
}
