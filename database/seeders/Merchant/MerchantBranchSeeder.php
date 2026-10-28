<?php

namespace Database\Seeders\Merchant;


use App\Support\Enum\StatusEnum;
use Domain\MarketPlace\Models\Merchant;
use Domain\MarketPlace\Models\MerchantBranch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MerchantBranchSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Model::unguard();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('merchant_branch_categories')->truncate();
        DB::table('merchant_branches')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $faker = \Faker\Factory::create();
        $faker_ar = \Faker\Factory::create('ar_AR');



        $vendors = Merchant::all();


        foreach ($vendors as $vendor) {

            for ($i = 0; $i <= 8; $i++) {

                $branch = MerchantBranch::create([
                    'longitude' => $faker->longitude,
                    'latitude' => $faker->latitude,
                    'description' => [
                        'en' => 'branch',
                        'ar' => 'branch',
                    ],
                    'type'=>$i==2?'mobile':'fixed',
                    'accept_multi_requests_at_same_time'=>$i==2?false:true,
                    'auto_accept'=>$i==2?true:false,
                    'has_scheduled_services'=>true,
                    'requests_per_hour_for_same_service'=>3,
                    'slot'=>120,
                    'title' => [
                        'en' => $faker->company,
                        'ar' => $faker_ar->company,
                    ],
                    'address_name'=>[
                        'en' => $faker->address,
                        'ar' => $faker_ar->address,
                    ],
                    'city_id'=>1,
                    'distract_id'=>1,
                    'merchant_id' => $vendor->id,
                ]);
                $branch->merchantCategories()->syncWithPivotValues($vendor->merchantCategories?->pluck('id')->toArray(), ['merchant_id' => $vendor->id,'status'=>StatusEnum::ACTIVE->value]);

            }
        }



    }
}
