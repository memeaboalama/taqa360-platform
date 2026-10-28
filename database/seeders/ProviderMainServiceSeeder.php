<?php

namespace Database\Seeders;

use App\Domain\AppBase\Models\Category\MainCategory;
use App\Domain\Provider\Models\ProviderCategory;
use App\Domain\Provider\Models\ProviderMainService;
use App\Support\Enum\MainCategoryType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProviderMainServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('provider_main_services')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $mainCategory = MainCategory::whereType(MainCategoryType::TOWING)->first();

        ProviderMainService::create([
            'title' =>  [
                'ar'    =>  'داخل المدينة',
                'en'    =>  'Inside city'
            ],
            'description' => [
                'ar'    =>  '',
                'en'    =>  '',
            ],
            'starter_price' =>  100,
            'provider_category_id'=> ProviderCategory::where('main_category_id',$mainCategory->id)->first()?->id,
            'main_category_id' => $mainCategory?->id,
        ]);


        ProviderMainService::create([
            'title' =>  [
                'ar'    =>  'بين المدن',
                'en'    =>  'Outside city'
            ],
            'description' => [
                'ar'    =>  '',
                'en'    =>  '',
            ],
            'starter_price' =>  200,
            'provider_category_id'=> ProviderCategory::where('main_category_id',$mainCategory->id)->first()?->id,
            'main_category_id' => $mainCategory?->id,
        ]);
    }
}
