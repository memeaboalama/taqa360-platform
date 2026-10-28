<?php

namespace Database\Seeders;

use App\Domain\AppBase\Models\Category\MainCategory;
use App\Domain\Provider\Models\ProviderCategory;
use App\Domain\Provider\Models\ProviderMainService;
use App\Domain\Provider\Models\ProviderSubService;
use App\Domain\Towing\Models\TowingType;
use App\Support\Enum\MainCategoryType;
use App\Support\Enum\ServiceStrategy;
use App\Support\Enum\StatusEnum;
use App\Support\Enum\TowingDomain;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProviderSubServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('provider_sub_services')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $mainCategory = MainCategory::whereType(MainCategoryType::TOWING)->first();
        $providerCategory = ProviderCategory::where('main_category_id',$mainCategory?->id)->first();
        $firstMainService = ProviderMainService::first();
        $secondMainService = ProviderMainService::latest()->first();

        ProviderSubService::create([
            'provider_main_service_id'  =>  $firstMainService?->id,
            'provider_category_id'      =>  $providerCategory->id,
            'main_category_id'          =>  $mainCategory->id,
            'title' =>  [
                'ar'    =>  'سطحه عادية',
                'en'    =>  'Normail towing'
            ],
            'price'             =>  200,
            'schedulable'       =>  StatusEnum::INACTIVE,
            'increasable'       =>  StatusEnum::INACTIVE,
            'strategy'          =>  ServiceStrategy::TOWING,
            'towing_domain'     => TowingDomain::INSIDE,
            'towing_type_id'    =>  TowingType::first()?->id,
            'towing_open_price' =>  200,
            'towing_price_per_km'   =>  1.5,
            'towing_price_per_minute'   =>  1,
        ]);


        ProviderSubService::create([
            'provider_main_service_id'  =>  $firstMainService?->id,
            'provider_category_id'      =>  $providerCategory->id,
            'main_category_id'          =>  $mainCategory->id,
            'title' =>  [
                'ar'    =>  'سطحه هيدروليك',
                'en'    =>  'Hydraulic towing'
            ],
            'price'             =>  200,
            'schedulable'       =>  StatusEnum::INACTIVE,
            'increasable'       =>  StatusEnum::INACTIVE,
            'strategy'          =>  ServiceStrategy::TOWING,
            'towing_domain'     => TowingDomain::INSIDE,
            'towing_type_id'    =>  TowingType::latest()->first()?->id,
            'towing_open_price' =>  300,
            'towing_price_per_km'   =>  2,
            'towing_price_per_minute'   =>  1.5,
        ]);




        ProviderSubService::create([
            'provider_main_service_id'  =>  $secondMainService?->id,
            'provider_category_id'      =>  $providerCategory->id,
            'main_category_id'          =>  $mainCategory->id,
            'title' =>  [
                'ar'    =>  'سطحه عادية',
                'en'    =>  'Normail towing'
            ],
            'price'             =>  200,
            'schedulable'       =>  StatusEnum::INACTIVE,
            'increasable'       =>  StatusEnum::INACTIVE,
            'strategy'          =>  ServiceStrategy::TOWING,
            'towing_domain'     => TowingDomain::OUTSIDE,
            'towing_type_id'    =>  TowingType::first()?->id,
            'towing_open_price' =>  0,
            'towing_price_per_km'   =>  0,
            'towing_price_per_minute'   =>  0,
        ]);


        ProviderSubService::create([
            'provider_main_service_id'  =>  $secondMainService?->id,
            'provider_category_id'      =>  $providerCategory->id,
            'main_category_id'          =>  $mainCategory->id,
            'title' =>  [
                'ar'    =>  'سطحه هيدروليك',
                'en'    =>  'Hydraulic towing'
            ],
            'price'             =>  200,
            'schedulable'       =>  StatusEnum::INACTIVE,
            'increasable'       =>  StatusEnum::INACTIVE,
            'strategy'          =>  ServiceStrategy::TOWING,
            'towing_domain'     => TowingDomain::OUTSIDE,
            'towing_type_id'    =>  TowingType::latest()->first()?->id,
            'towing_open_price' =>  0,
            'towing_price_per_km'   =>  0,
            'towing_price_per_minute'   =>  0,
        ]);

    }
}
