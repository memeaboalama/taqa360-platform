<?php

namespace Database\Seeders\Merchant;


use App\Domain\AppBase\Models\Location\Country;
use App\Support\Enum\StatusEnum;
use Domain\MarketPlace\Models\MarketPlaceCategory;
use Domain\MarketPlace\Models\Merchant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MerchantSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Model::unguard();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('merchants')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $faker = \Faker\Factory::create();
        $faker_ar = \Faker\Factory::create('ar_AR');
        $vendors = [
            1 => [
                'name' => 'merchant',
                'user_name' => $faker->userName,
                'email' => 'merchant@gmail.com',
                'phone' => '996353533',
                'description' => [
                    'en' => 'Category1',
                    'ar' => 'Category1',
                ],
                'company_name' => [
                    'en' => 'Category1',
                    'ar' => 'Category1',
                ],
                'status' => StatusEnum::ACTIVE->value,

            ],
            2 => [
                'name' => 'merchant1',
                'user_name' => $faker->userName,
                'email' => 'merchant1@gmail.com',
                'phone' => '986353343',
                'description' => [
                    'en' => 'Category1',
                    'ar' => 'Category1',
                ],
                'company_name' => [
                    'en' => 'Category1',
                    'ar' => 'Category1',
                ],
                'status' => StatusEnum::ACTIVE->value,
            ],
            3 => [
                'name' => 'merchant2',
                'user_name' => $faker->userName,
                'email' => 'merchant2@gmail.com',
                'phone' => '988353533',
                'description' => [
                    'en' => 'Category1',
                    'ar' => 'Category1',
                ],
                'company_name' => [
                    'en' => 'Category1',
                    'ar' => 'Category1',
                ],
                'status' => StatusEnum::ACTIVE->value,
            ],
            4 => [
                'name' => 'merchant3',
                'user_name' => $faker->userName,
                'email' => 'merchant3@gmail.com',
                'phone' => '983353533',
                'description' => [
                    'en' => 'Category1',
                    'ar' => 'Category1',
                ],
                'company_name' => [
                    'en' => 'Category1',
                    'ar' => 'Category1',
                ],
                'status' => StatusEnum::ACTIVE->value,
            ],
            5 => [
                'name' => 'merchant4',
                'user_name' => $faker->userName,
                'email' => 'merchant4@gmail.com',
                'phone' => '986453533',
                'description' => [
                    'en' => 'eats',
                    'ar' => 'eats',
                ],
                'company_name' => [
                    'en' => 'eats',
                    'ar' => 'eats',
                ],
                'status' => StatusEnum::ACTIVE->value,
            ],
            6 => [
                'name' => 'merchant5',
                'user_name' => $faker->userName,
                'email' => 'merchant5@gmail.com',
                'phone' => '986353531',
                'description' => [
                    'en' => 'Eats.com',
                    'ar' => 'Eats.com',
                ],
                'company_name' => [
                    'en' => 'Eats.com',
                    'ar' => 'Eats.com',
                ],
                'status' => StatusEnum::ACTIVE->value,
            ],
            7 => [
                'name' => 'merchant6',
                'user_name' => $faker->userName,
                'email' => 'merchant6@gmail.com',
                'phone' => '986353532',
                'description' => [
                    'en' => 'Category1',
                    'ar' => 'Category1',
                ],
                'company_name' => [
                    'en' => 'Category1',
                    'ar' => 'Category1',
                ],
                'status' => StatusEnum::ACTIVE->value,
            ],
            8 => [
                'name' => 'merchant7',
                'user_name' => $faker->userName,
                'email' => 'merchant7@gmail.com',
                'phone' => '986353534',
                'description' => [
                    'en' => 'Water Way',
                    'ar' => 'Water Way',
                ],
                'company_name' => [
                    'en' => 'Water Way',
                    'ar' => 'Water Way',
                ],

            ],
            9 => [
                'name' => $faker->name,
                'user_name' => $faker->userName,
                'email' => $faker->email,
                'phone' => $faker->phoneNumber,
                'description' => [
                    'en' => $faker->text,
                    'ar' => $faker_ar->text,
                ],
                'company_name' => [
                    'en' => $faker->company,
                    'ar' => $faker_ar->company,
                ],

            ],
            10 => [
                'name' => $faker->name,
                'user_name' => $faker->userName,
                'email' => $faker->email,
                'phone' => $faker->phoneNumber,
                'description' => [
                    'en' => $faker->text,
                    'ar' => $faker_ar->text,
                ],
                'company_name' => [
                    'en' => $faker->company,
                    'ar' => $faker_ar->company,
                ],

            ],
            11 => [
                'name' => $faker->name,
                'user_name' => $faker->userName,
                'email' => $faker->email,
                'phone' => $faker->phoneNumber,
                'description' => [
                    'en' => $faker->text,
                    'ar' => $faker_ar->text,
                ],
                'company_name' => [
                    'en' => $faker->company,
                    'ar' => $faker_ar->company,
                ],

            ],
            12 => [
                'name' => $faker->name,
                'user_name' => $faker->userName,
                'email' => $faker->email,
                'phone' => $faker->phoneNumber,
                'description' => [
                    'en' => $faker->text,
                    'ar' => $faker_ar->text,
                ],
                'company_name' => [
                    'en' => $faker->company,
                    'ar' => $faker_ar->company,
                ],

            ],
            13 => [
                'name' => $faker->name,
                'user_name' => $faker->userName,
                'email' => $faker->email,
                'phone' => $faker->phoneNumber,
                'description' => [
                    'en' => $faker->text,
                    'ar' => $faker_ar->text,
                ],
                'company_name' => [
                    'en' => $faker->company,
                    'ar' => $faker_ar->company,
                ],

            ],
            14 => [
                'name' => $faker->name,
                'user_name' => $faker->userName,
                'email' => $faker->email,
                'phone' => $faker->phoneNumber,
                'description' => [
                    'en' => $faker->text,
                    'ar' => $faker_ar->text,
                ],
                'company_name' => [
                    'en' => $faker->company,
                    'ar' => $faker_ar->company,
                ],

            ],
            15 => [
                'name' => $faker->name,
                'user_name' => $faker->userName,
                'email' => $faker->email,
                'phone' => $faker->phoneNumber,
                'description' => [
                    'en' => $faker->text,
                    'ar' => $faker_ar->text,
                ],
                'company_name' => [
                    'en' => $faker->company,
                    'ar' => $faker_ar->company,
                ],

            ],
            16 => [
                'name' => $faker->name,
                'user_name' => $faker->userName,
                'email' => $faker->email,
                'phone' => $faker->phoneNumber,
                'description' => [
                    'en' => $faker->text,
                    'ar' => $faker_ar->text,
                ],
                'company_name' => [
                    'en' => $faker->company,
                    'ar' => $faker_ar->company,
                ],

            ],
            17 => [
                'name' => $faker->name,
                'user_name' => $faker->userName,
                'email' => $faker->email,
                'phone' => $faker->phoneNumber,
                'description' => [
                    'en' => $faker->text,
                    'ar' => $faker_ar->text,
                ],
                'company_name' => [
                    'en' => $faker->company,
                    'ar' => $faker_ar->company,
                ],

            ],
            18 => [
                'name' => $faker->name,
                'user_name' => $faker->userName,
                'email' => $faker->email,
                'phone' => $faker->phoneNumber,
                'description' => [
                    'en' => $faker->text,
                    'ar' => $faker_ar->text,
                ],
                'company_name' => [
                    'en' => $faker->company,
                    'ar' => $faker_ar->company,
                ],

            ],
            19 => [
                'name' => $faker->name,
                'user_name' => $faker->userName,
                'email' => $faker->email,
                'phone' => $faker->phoneNumber,
                'description' => [
                    'en' => $faker->text,
                    'ar' => $faker_ar->text,
                ],
                'company_name' => [
                    'en' => $faker->company,
                    'ar' => $faker_ar->company,
                ],

            ],
            20 => [
                'name' => $faker->name,
                'user_name' => $faker->userName,
                'email' => $faker->email,
                'phone' => $faker->phoneNumber,
                'description' => [
                    'en' => $faker->text,
                    'ar' => $faker_ar->text,
                ],
                'company_name' => [
                    'en' => $faker->company,
                    'ar' => $faker_ar->company,
                ],

            ],
            //////////

            21 => [
                'name' => $faker->name,
                'user_name' => $faker->userName,
                'email' => $faker->email,
                'phone' => $faker->phoneNumber,
                'description' => [
                    'en' => $faker->text,
                    'ar' => $faker_ar->text,
                ],
                'company_name' => [
                    'en' => $faker->company,
                    'ar' => $faker_ar->company,
                ],

            ],
            22 => [
                'name' => $faker->name,
                'user_name' => $faker->userName,
                'email' => $faker->email,
                'phone' => $faker->phoneNumber,
                'description' => [
                    'en' => $faker->text,
                    'ar' => $faker_ar->text,
                ],
                'company_name' => [
                    'en' => $faker->company,
                    'ar' => $faker_ar->company,
                ],

            ],
            23 => [
                'name' => $faker->name,
                'user_name' => $faker->userName,
                'email' => $faker->email,
                'phone' => $faker->phoneNumber,
                'description' => [
                    'en' => $faker->text,
                    'ar' => $faker_ar->text,
                ],
                'company_name' => [
                    'en' => $faker->company,
                    'ar' => $faker_ar->company,
                ],

            ],
            24 => [
                'name' => $faker->name,
                'user_name' => $faker->userName,
                'email' => $faker->email,
                'phone' => $faker->phoneNumber,
                'description' => [
                    'en' => $faker->text,
                    'ar' => $faker_ar->text,
                ],
                'company_name' => [
                    'en' => $faker->company,
                    'ar' => $faker_ar->company,
                ],

            ],
            25 => [
                'name' => $faker->name,
                'user_name' => $faker->userName,
                'email' => $faker->email,
                'phone' => $faker->phoneNumber,
                'description' => [
                    'en' => $faker->text,
                    'ar' => $faker_ar->text,
                ],
                'company_name' => [
                    'en' => $faker->company,
                    'ar' => $faker_ar->company,
                ],

            ],
            26 => [
                'name' => $faker->name,
                'user_name' => $faker->userName,
                'email' => $faker->email,
                'phone' => $faker->phoneNumber,
                'description' => [
                    'en' => $faker->text,
                    'ar' => $faker_ar->text,
                ],
                'company_name' => [
                    'en' => $faker->company,
                    'ar' => $faker_ar->company,
                ],

            ],
            27 => [
                'name' => $faker->name,
                'user_name' => $faker->userName,
                'email' => $faker->email,
                'phone' => $faker->phoneNumber,
                'description' => [
                    'en' => $faker->text,
                    'ar' => $faker_ar->text,
                ],
                'company_name' => [
                    'en' => $faker->company,
                    'ar' => $faker_ar->company,
                ],

            ],
            28 => [
                'name' => $faker->name,
                'user_name' => $faker->userName,
                'email' => $faker->email,
                'phone' => $faker->phoneNumber,
                'description' => [
                    'en' => $faker->text,
                    'ar' => $faker_ar->text,
                ],
                'company_name' => [
                    'en' => $faker->company,
                    'ar' => $faker_ar->company,
                ],

            ],
            29 => [
                'name' => $faker->name,
                'user_name' => $faker->userName,
                'email' => $faker->email,
                'phone' => $faker->phoneNumber,
                'description' => [
                    'en' => $faker->text,
                    'ar' => $faker_ar->text,
                ],
                'company_name' => [
                    'en' => $faker->company,
                    'ar' => $faker_ar->company,
                ],

            ],
            30 => [
                'name' => $faker->name,
                'user_name' => $faker->userName,
                'email' => $faker->email,
                'phone' => $faker->phoneNumber,
                'description' => [
                    'en' => $faker->text,
                    'ar' => $faker_ar->text,
                ],
                'company_name' => [
                    'en' => $faker->company,
                    'ar' => $faker_ar->company,
                ],

            ],
            31 => [
                'name' => $faker->name,
                'user_name' => $faker->userName,
                'email' => $faker->email,
                'phone' => $faker->phoneNumber,
                'description' => [
                    'en' => $faker->text,
                    'ar' => $faker_ar->text,
                ],
                'company_name' => [
                    'en' => $faker->company,
                    'ar' => $faker_ar->company,
                ],

            ],
            32 => [
                'name' => $faker->name,
                'user_name' => $faker->userName,
                'email' => $faker->email,
                'phone' => $faker->phoneNumber,
                'description' => [
                    'en' => $faker->text,
                    'ar' => $faker_ar->text,
                ],
                'company_name' => [
                    'en' => $faker->company,
                    'ar' => $faker_ar->company,
                ],

            ],
            33 => [
                'name' => $faker->name,
                'user_name' => $faker->userName,
                'email' => $faker->email,
                'phone' => $faker->phoneNumber,
                'description' => [
                    'en' => $faker->text,
                    'ar' => $faker_ar->text,
                ],
                'company_name' => [
                    'en' => $faker->company,
                    'ar' => $faker_ar->company,
                ],

            ],
            34 => [
                'name' => $faker->name,
                'user_name' => $faker->userName,
                'email' => $faker->email,
                'phone' => $faker->phoneNumber,
                'description' => [
                    'en' => $faker->text,
                    'ar' => $faker_ar->text,
                ],
                'company_name' => [
                    'en' => $faker->company,
                    'ar' => $faker_ar->company,
                ],

            ],
            35 => [
                'name' => $faker->name,
                'user_name' => $faker->userName,
                'email' => $faker->email,
                'phone' => $faker->phoneNumber,
                'description' => [
                    'en' => $faker->text,
                    'ar' => $faker_ar->text,
                ],
                'company_name' => [
                    'en' => $faker->company,
                    'ar' => $faker_ar->company,
                ],

            ],
            36 => [
                'name' => $faker->name,
                'user_name' => $faker->userName,
                'email' => $faker->email,
                'phone' => $faker->phoneNumber,
                'description' => [
                    'en' => $faker->text,
                    'ar' => $faker_ar->text,
                ],
                'company_name' => [
                    'en' => $faker->company,
                    'ar' => $faker_ar->company,
                ],

            ],
            37 => [
                'name' => $faker->name,
                'user_name' => $faker->userName,
                'email' => $faker->email,
                'phone' => $faker->phoneNumber,
                'description' => [
                    'en' => $faker->text,
                    'ar' => $faker_ar->text,
                ],
                'company_name' => [
                    'en' => $faker->company,
                    'ar' => $faker_ar->company,
                ],

            ],
            38 => [
                'name' => $faker->name,
                'user_name' => $faker->userName,
                'email' => $faker->email,
                'phone' => $faker->phoneNumber,
                'description' => [
                    'en' => $faker->text,
                    'ar' => $faker_ar->text,
                ],
                'company_name' => [
                    'en' => $faker->company,
                    'ar' => $faker_ar->company,
                ],

            ],
            39 => [
                'name' => $faker->name,
                'user_name' => $faker->userName,
                'email' => $faker->email,
                'phone' => $faker->phoneNumber,
                'description' => [
                    'en' => $faker->text,
                    'ar' => $faker_ar->text,
                ],
                'company_name' => [
                    'en' => $faker->company,
                    'ar' => $faker_ar->company,
                ],

            ],
            40 => [
                'name' => $faker->name,
                'user_name' => $faker->userName,
                'email' => $faker->email,
                'phone' => $faker->phoneNumber,
                'description' => [
                    'en' => $faker->text,
                    'ar' => $faker_ar->text,
                ],
                'company_name' => [
                    'en' => $faker->company,
                    'ar' => $faker_ar->company,
                ],

            ],
        ];

        foreach ($vendors as $vendor) {
            $data = Merchant::create([
                'name' => $vendor["name"],
                'user_name' => $vendor["user_name"],
                'email' => $vendor["email"],
                'phone' => $vendor["phone"],
                'password' => Hash::make('password'),
                'company_name' => $vendor["company_name"],
                'description' => $vendor["description"],
                'status' => StatusEnum::ACTIVE->value,
                'country_id' => Country::first()->id,
            ]);
            $data->categories()->sync(MarketPlaceCategory::all()->modelKeys());

        }

    }
}
