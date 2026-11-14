<?php

namespace Database\Seeders\AppBase;

use App\Domain\AppBase\Models\Category\Package;
use App\Domain\AppBase\Models\PackagePackages;
use App\Support\Enum\PackageType;
use App\Support\Enum\StatusEnum;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // php artisan db:seed --class=Database\\Seeders\\AppBase\\PackageSeeder

        \App\Domain\MarketPlace\Models\Package::query()->firstOrCreate(
            ['id' => 1],
            [
                'title' => json_encode([
                    'ar'    =>  'الافتراضي',
                    'en'    =>  'default',
                ]),
                'commission' => 10.0,
                'status' => 'active',
            ]
        );
    }
}