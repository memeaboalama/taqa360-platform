<?php

namespace Database\Seeders\Merchant;

use App\Support\Enum\PriorityEnum;
use App\Support\Enum\StatusEnum;
use Domain\MarketPlace\Models\Merchant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MerchantCategorySeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('merchant_categories')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            [
                'title'  =>  [
                    'ar'    =>  'Cat',
                    'en'    =>  'Cat',
                ],
                'priority'=>PriorityEnum::HIGH->value,

            ],
            [
                'title'  =>  [
                    'ar'    =>  'Category1',
                    'en'    =>  'Category1',
                ],
                'priority'=>PriorityEnum::MEDUIM->value,

            ],
            [
                'title'  =>  [
                    'ar'    =>  'Category2',
                    'en'    =>  'Category2',
                ],
                'priority'=>PriorityEnum::LOW->value,

            ],

        ];
        foreach (Merchant::all() as $value){
            foreach ($data as $record)
                $value->merchantCategories()->create([
                    'title'=>$record["title"],
                    'priority'=>$record["priority"],
                    'status'=>StatusEnum::ACTIVE->value,
                ]);
        }

    }
}
