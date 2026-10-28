<?php

namespace Database\Seeders\Merchant;


use App\Support\Enum\StatusEnum;
use Domain\MarketPlace\Models\Merchant;
use Domain\MarketPlace\Models\MerchantBranch;
use Domain\MarketPlace\Models\MerchantEmployee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MerchantEmployeeSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Model::unguard();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('merchant_employees')->truncate();
        DB::table('merchant_branch_employees')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $faker = \Faker\Factory::create();
        $faker_ar = \Faker\Factory::create('ar_AR');


        $vendors = Merchant::all();


        foreach ($vendors as $vendor) {

            for ($i = 0; $i <= 1; $i++) {

                $employee = MerchantEmployee::create([
                    'name' => $faker->name,
                    'email' => "merchant_$vendor->id$i@gmail.com",
                    'phone' => "09876543$vendor->id$i",
                    'password' => Hash::make('123456789'),
                    'dialcode' => "965",
                    'country_id' => 1,
                    'country_name' => "saudi arabia",
                    'country_code' => "sa",
                    'status' => "active",
                    'last_logged_in_at' => now(),
                    'merchant_id' => $vendor->id,


                ]);
                $employee->branch()->sync($vendor->branches?->pluck('id')->toArray());

            }


        }


        foreach (MerchantBranch::all() as $value) {
            $id = $value->employees()->first()->id;
            $value->update(["manager_id" => $id]);
        }


    }
}
