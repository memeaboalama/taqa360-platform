<?php

namespace Database\Seeders;

use App\Domain\AppBase\Models\Category\MainCategory;
use App\Domain\Provider\Models\ProviderCategory;
use App\Support\Enum\MainCategoryType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProviderCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('provider_categories')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        ProviderCategory::create([
            'title' =>  [
                'ar'    =>  'سطحات',
                'en'    =>  'towing',
            ],
            'main_category_id'  =>  MainCategory::whereType(MainCategoryType::TOWING)->first()?->id
        ]);
    }
}
