<?php

namespace Database\Seeders\Merchant;

use App\Support\Enum\OptionAddonType;
use App\Support\Enum\StatusEnum;
use Domain\MarketPlace\Models\MerchantService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MerchantAddonCategorySeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Model::unguard();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('merchant_service_addons_category')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            [
                'title'  =>  [
                    'ar'    =>  'Category1',
                    'en'    =>  'Category1',
                ],
                'option_type'=>OptionAddonType::MULTI->value,
                'min_required'=>0,

            ],
            [
                'title'  =>  [
                    'ar'    =>  'Category1',
                    'en'    =>  'Category1',
                ],
                'option_type'=>OptionAddonType::MULTI->value,
                'min_required'=>0,

            ],
            [
                'title'  =>  [
                    'ar'    =>  'Category1',
                    'en'    =>  'Category1',
                ],
                'option_type'=>OptionAddonType::MULTI->value,
                'min_required'=>0,

            ],
        ];
        foreach (MerchantService::all() as $value){
            foreach ($data as $record)
                $value->addon_categories()->create([
                    'title'=>$record["title"],
                    'option_type'=>$record["option_type"],
                    'min_required'=>$record["min_required"],
                    'merchant_id'=>$value->merchant_id,
                    'status'=>StatusEnum::ACTIVE->value,
                ]);
        }

    }
}
