<?php

namespace Database\Seeders\Merchant;


use App\Support\Enum\StatusEnum;
use Domain\MarketPlace\Models\MerchantAddonCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MerchantAddonSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Model::unguard();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('merchant_service_addons')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            [
                'title'  =>  [
                    'ar'    =>  'small',
                    'en'    =>  'small',
                ],
                'price'=>10,
                'working_minutes'=>20,

            ],
            [
                'title'  =>  [
                    'ar'    =>  'medium',
                    'en'    =>  'medium',
                ],
                'price'=>20,
                'working_minutes'=>10,

            ],
            [
                'title'  =>  [
                    'ar'    =>  'Big',
                    'en'    =>  'Big',
                ],
                'price'=>15,
                'working_minutes'=>5,

            ],

        ];
        foreach (MerchantAddonCategory::all() as $value){
            foreach ($data as $record)
                $value->addons()->create([
                    'title'=>$record["title"],
                    'price'=>$record["price"],
                    'working_minutes'=>$record["working_minutes"],
                    'merchant_id'=>$value->merchant_id,
                    'merchant_service_id'=>$value->merchant_service_id,
                    'status'=>StatusEnum::ACTIVE->value,
                ]);
        }

    }
}
