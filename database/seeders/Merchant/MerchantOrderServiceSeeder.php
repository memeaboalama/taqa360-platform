<?php

namespace Database\Seeders\Merchant;

use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\User\Models\User;
use Domain\MarketPlace\Models\Merchant;
use Domain\MarketPlace\Models\MerchantBranch;
use Domain\MarketPlace\Models\MerchantOrderService;
use Domain\MarketPlace\Models\MerchantService;
use Faker\Generator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MerchantOrderServiceSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('merchant_order_services')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $faker = \Faker\Factory::create();


            foreach (MerchantOrder::all() as $value) {
                MerchantOrderService::create([
                    'user_id' => $value->user_id,
                    'merchant_id' => $value->merchant_id,
                    'order_id' =>$value->id,
                    'merchant_branch_id' => $value?->merchant_branch_id,
                    'merchant_service_id' =>$value->merchant?->services()?->first()->id,
                    'service_name' => $value->merchant?->services()?->first()->getTranslations('title'),
                    'service_total' => $faker->numberBetween(100, 500),
                    'vat' => $faker->numberBetween(10, 26),
                    'total_after_vat' => $faker->numberBetween(1000, 5000),
                ]);


        }
    }
}
