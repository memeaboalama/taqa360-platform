<?php

namespace Database\Seeders\Merchant;

use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\User\Models\User;
use App\Domain\User\Models\UserAddress;
use App\Support\Enum\PaymentMethod;
use Domain\MarketPlace\Models\Merchant;
use Domain\MarketPlace\Models\MerchantBranch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MerchantOrderSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('merchant_orders')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $users = User::count();
        $address = UserAddress::whereIn('user_id', User::pluck('id'))->first();
//        $merchants = Merchant::count();
//        $branch = MerchantBranch::whereIn('merchant_id', Merchant::pluck('id'))->first();
        $statuses = MerchantOrderStatuses::cases();
        $payment = PaymentMethod::cases();
        $faker = \Faker\Factory::create();
        $faker_ar = \Faker\Factory::create('ar_AR');
        foreach (Merchant::all() as $value) {

            foreach ($value->branches as $branch){
                MerchantOrder::create([
                    'code'=>"0000$branch->id$value->id",
                    'user_id' => $faker->numberBetween(1, $users),
                    'merchant_id' => $value->id,
                    'address_id' => $address?->id,
                    'merchant_branch_id' => $branch?->id,
                    'order_type' => $branch?->type,
                    'date' => $faker->dateTimeThisMonth(),
                    'from' => $faker->time(),
                    'to' => $faker->time(),
                    'schedulable' => 1,
                    'dialcode' => 966,
                    'payment_method' => $payment[array_rand($payment)]->value,
                    'merchant_name' => $value->company_name,
                    'client_lat' => $faker->latitude(),
                    'client_long' => $faker->longitude(),
                    'client_phone' => $faker->phoneNumber(),
                    'client_name' => $faker->name(),
                    'payment_type' => $faker->creditCardType(),
                    'channel' => "mobile",
                    'subtotal' => $faker->numberBetween(2000, 10000),
                    'vat' => $faker->numberBetween(0, 26),
                    'total_after_vat' => $faker->numberBetween(2000, 10000),
                    'total_addon' => $faker->numberBetween(2000, 10000),
                    'total_service' => $faker->numberBetween(2000, 10000),
                    'discount' => $faker->numberBetween(200, 1000),
                    'total_after_discount' => $faker->numberBetween(2000, 10000),
                    'status' => $statuses[array_rand($statuses)]->value,
                ]);
            }

        }
    }


}
