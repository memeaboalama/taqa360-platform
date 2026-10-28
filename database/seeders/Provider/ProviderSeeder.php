<?php

namespace Database\Seeders\Provider;

use App\Domain\AppBase\Models\Car\Brand;
use App\Domain\AppBase\Models\Car\Model;
use App\Domain\AppBase\Models\Location\City;
use App\Domain\AppBase\Models\Location\Country;
use App\Domain\AppBase\Models\Nationality\Nationality;
use App\Domain\Provider\Models\Company;
use App\Domain\Provider\Models\Provider;
use App\Support\Enum\NetworkStatusEnum;
use App\Support\Enum\StatusEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use MatanYadaev\EloquentSpatial\Objects\Point;

class ProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('providers')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Provider::create([
            'country_id'    =>  Country::first()?->id,
            'city_id'    =>  City::first()?->id,
            'nationality_id'    =>  Nationality::first()?->id,
            'company_id'    =>  Company::first()?->id,
            'name'          =>  'demo driver',
            'phone'             =>  '511112233',
            'phone_verified_at'  =>  now(),
            'location'          => new Point(29.9898014,31.2529412,4326),
            'network_status'    =>  NetworkStatusEnum::OFFLINE,
        ])?->vehicles()->create([
            'model_id'  =>  Model::first()->id,
            'brand_id'  =>  Brand::first()->id,
            'plate_number'  =>  'vfd 432',
            'year'  =>  2020,
            'is_towing'  =>  true,
            'towing_type_id'  =>  1,
        ]);

        Provider::create([
            'nationality_id'    =>  Nationality::first()?->id,
            'country_id'    =>  Country::first()?->id,
            'name'          =>  'demo driver',
            'phone'             =>  '511112264',
            'phone_verified_at'  =>  now(),
            'location'          => new Point(29.9898014,31.2529412,4326),
            'network_status'    =>  NetworkStatusEnum::OFFLINE,
        ])?->vehicles()->create([
            'model_id'  =>  Model::first()->id,
            'brand_id'  =>  Brand::first()->id,
            'plate_number'  =>  'asad 662',
            'year'  =>  2020,
            'is_towing'  =>  true,
            'towing_type_id'  =>  2,
        ]);
    }
}
