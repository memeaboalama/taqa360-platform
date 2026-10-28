<?php

namespace Database\Seeders\Admin;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Domain\AppBase\Models\Offer;

class OfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('offers')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        Offer::create([
            'name'=>"عرض أول طلب",
            'usages_left'=>"1",
            'limit_per_user'=>"1",
            'min_order_value'=>"50",
            'max_discount'=>"500",
            'period'=>"10",
            'type'=>"flat",
            'type_discount'=>"order",
            'discount_value'=>"50",
            'status'=>"inactive",
            "is_custom"=>"0"
        ]);
    }
}
