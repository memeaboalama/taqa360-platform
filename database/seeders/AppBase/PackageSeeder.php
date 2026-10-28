<?php

namespace Database\Seeders\AppBase;

use App\Domain\AppBase\Models\Category\Package;
use App\Domain\AppBase\Models\PackagePackages;
use App\Support\Enum\PackageType;
use App\Support\Enum\StatusEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('packages')->truncate();

        // php artisan db:seed --class=Database\\Seeders\\AppBase\\PackageSeeder

        
            \App\Domain\MarketPlace\Models\Package::upsert(
                [
                    // ['id'=>1,'name'=>'default',    'desc'=>'الافتراضي', 'status'=>'active'],
                    ['id'=>1,'title'  =>  json_encode([
                    'ar'    =>  'الافتراضي',
                    'en'    =>  'default',
                ]), 'commission'=>10.0,'status'=>'active'],
                    // ['id'=>1,'name'=>'with_delivery',    'desc'=>'مع التوصيل', 'status'=>'active'],
                    // ['id'=>2,'name'=>'without_delivery', 'desc'=>'بدون توصيل', 'status'=>'active'],
                ],
                ['id'],                  // key to detect duplicates
                ['title','description','status','commission'] // columns to update on duplicate
            );
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}