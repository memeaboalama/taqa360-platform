<?php

namespace Database\Seeders\AppBase;

use App\Domain\AppBase\Models\Category\TypeService;
use App\Domain\AppBase\Models\PackageTypeServices;
use App\Support\Enum\TypeServiceType;
use App\Support\Enum\StatusEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('type_services')->truncate();

        // php artisan db:seed --class=Database\\Seeders\\AppBase\\TypeServiceSeeder

        
            \App\Domain\AppBase\Models\TypeService::upsert(
                [
                    ['id'=>1,'name'=>'default',    'desc'=>'الافتراضي', 'status'=>'active'],
                    // ['id'=>1,'name'=>'with_delivery',    'desc'=>'مع التوصيل', 'status'=>'active'],
                    // ['id'=>2,'name'=>'without_delivery', 'desc'=>'بدون توصيل', 'status'=>'active'],
                ],
                ['id'],                  // key to detect duplicates
                ['name','desc','status'] // columns to update on duplicate
            );
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}