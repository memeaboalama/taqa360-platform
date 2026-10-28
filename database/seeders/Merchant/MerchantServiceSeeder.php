<?php

namespace Database\Seeders\Merchant;

use App\Domain\AppBase\Models\Setting\Currency;
use App\Support\Enum\StatusEnum;
use Domain\MarketPlace\Models\Merchant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MerchantServiceSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Model::unguard();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('merchant_services_category')->truncate();
        DB::table('merchant_services')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            [
                'title' => [
                    'ar' => 'Menu',
                    'en' => 'Menu1',
                ],
                'description' => [
                    'ar' => 'Menu',
                    'en' => 'Menu1',
                ],
                'default_price' => 100,

            ],
            [
                'title' => [
                    'ar' => 'Menu',
                    'en' => 'Menu2',
                ],
                'description' => [
                    'ar' => 'Menu',
                    'en' => 'Menu2',
                ],
                'default_price' => 140,

            ],
            [
                'title' => [
                    'ar' => 'Menu',
                    'en' => 'Menu3',
                ],
                'description' => [
                    'ar' => 'Menu',
                    'en' => 'Menu3',
                ],
                'default_price' => 200,

            ],
            [
                'title' => [
                    'ar' => 'Menu',
                    'en' => 'Menu4',
                ],
                'description' => [
                    'ar' => 'Menu',
                    'en' => 'Menu4',
                ],
                'default_price' => 100,

            ],
            [
                'title' => [
                    'ar' => 'Menu',
                    'en' => 'Menu5',
                ],
                'description' => [
                    'ar' => 'Menu',
                    'en' => 'Menu5',
                ],
                'default_price' => 140,

            ],
            [
                'title' => [
                    'ar' => 'Menu',
                    'en' => 'Menu6',
                ],
                'description' => [
                    'ar' => 'Menu',
                    'en' => 'Menu6',
                ],
                'default_price' => 200,

            ],
            [
                'title' => [
                    'ar' => 'Menu',
                    'en' => 'Menu7',
                ],
                'description' => [
                    'ar' => 'Menu',
                    'en' => 'Menu7',
                ],
                'default_price' => 100,

            ],
            [
                'title' => [
                    'ar' => 'Menu',
                    'en' => 'Menu8',
                ],
                'description' => [
                    'ar' => 'Menu',
                    'en' => 'Menu8',
                ],
                'default_price' => 140,

            ],
            [
                'title' => [
                    'ar' => 'Menu',
                    'en' => 'Menu9',
                ],
                'description' => [
                    'ar' => 'Menu',
                    'en' => 'Menu9',
                ],
                'default_price' => 200,

            ],
            [
                'title' => [
                    'ar' => 'Menu',
                    'en' => 'Menu10',
                ],
                'description' => [
                    'ar' => 'Menu',
                    'en' => 'Menu10',
                ],
                'default_price' => 100,

            ],
            [
                'title' => [
                    'ar' => 'Menu',
                    'en' => 'Menu11',
                ],
                'description' => [
                    'ar' => 'Menu',
                    'en' => 'Menu11',
                ],
                'default_price' => 140,

            ],
            [
                'title' => [
                    'ar' => 'Menu',
                    'en' => 'Menu12',
                ],
                'description' => [
                    'ar' => 'Menu',
                    'en' => 'Menu12',
                ],
                'default_price' => 200,

            ],
            [
                'title' => [
                    'ar' => 'Menu',
                    'en' => 'Menu13',
                ],
                'description' => [
                    'ar' => 'Menu',
                    'en' => 'Menu13',
                ],
                'default_price' => 100,

            ],
            [
                'title' => [
                    'ar' => 'Menu',
                    'en' => 'Menu14',
                ],
                'description' => [
                    'ar' => 'Menu',
                    'en' => 'Menu14',
                ],
                'default_price' => 140,

            ],
            [
                'title' => [
                    'ar' => 'Menu',
                    'en' => 'Menu15',
                ],
                'description' => [
                    'ar' => 'Menu',
                    'en' => 'Menu15',
                ],
                'default_price' => 200,

            ],

        ];
        foreach (Merchant::all() as $value) {
            foreach ($data as $record) {
                $service = $value->services()->create([
                    'title' => $record["title"],
                    'description' => $record["description"],
                    'default_price' => $record["default_price"],
                    'status' => StatusEnum::ACTIVE->value,
                ]);

                //                DB::table('merchant_services_category')->insert([
                //                    'merchant_id'=>$value->id,
                //                    'merchant_branch_id'=>$value->branches()->first()->id,
                //                    'merchant_service_id'=>$service->id,
                //                    'merchant_category_id'=>$value->merchantCategories()->first()->id,
                //
                //                ]);

                foreach ($value->branches as $branch) {
                    $service->categories()->syncWithPivotValues(
                        $value->merchantCategories?->pluck('id')->toArray(),
                        [
                            'merchant_id' => $value->id,
                            'merchant_branch_id' => $branch?->id
                        ]
                    );
                }
            }
        }
    }
}
