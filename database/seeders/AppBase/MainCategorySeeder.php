<?php

namespace Database\Seeders\AppBase;

use App\Domain\AppBase\Models\Category\MainCategory;
use App\Support\Enum\MainCategoryType;
use App\Support\Enum\StatusEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MainCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('main_categories')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            [
                'title'  =>  [
                    'ar'    =>  'Category1',
                    'en'    =>  'Category1',
                ],
                'type'=>MainCategoryType::SERVICE->value,

            ],
            [
                'title'  =>  [
                    'ar'    =>  'Category2',
                    'en'    =>  'Category2',
                ],
                'type'=>MainCategoryType::TOWING->value,

            ],
            [
                'title'  =>  [
                    'ar'    =>  'Category3',
                    'en'    =>  'Category3',
                ],
                'type'=>MainCategoryType::MARKET->value,

            ],

        ];
        foreach ($data as $record)
            MainCategory::create([
                'title'=>$record["title"],
                'type'=>$record["type"],
                'status'=>StatusEnum::ACTIVE->value,
            ]);
    }
}
