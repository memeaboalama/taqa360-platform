<?php

namespace Database\Seeders\Merchant;

use App\Domain\AppBase\Models\City;
use App\Support\Enum\StatusEnum;
use Domain\MarketPlace\Models\MarketPlaceCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarketPlaceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Model::unguard();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('market_place_categories')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $cat = [
            1 => [
                'title' => [
                    'en' => 'Category1',
                    'ar' => 'Category1',
                ],
                'status' => StatusEnum::ACTIVE->value,
                'sort' => 1

            ],
            2 => [
                'title' => [
                    'en' => 'Category2',
                    'ar' => 'Category2',
                ],
                'status' => StatusEnum::ACTIVE->value,
                'sort' => 2
            ],

            3 => [
                'title' => [
                    'en' => 'Category3',
                    'ar' => 'Category3',
                ],
                'status' => StatusEnum::ACTIVE->value,
                'sort' => 3
            ],
            4 => [
                'title' => [
                    'en' => 'Category4',
                    'ar' => 'Category4',
                ],
                'status' => StatusEnum::ACTIVE->value,
                'sort' => 4
            ],


        ];
        foreach ($cat as $vendor) {
            $data = MarketPlaceCategory::create([
                'title' => $vendor["title"],
                'status' => $vendor["status"],
                'sort' => $vendor["sort"],

            ]);

        }
    }
}
