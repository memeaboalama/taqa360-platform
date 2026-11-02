<?php
// D:\Newtouch\Projects\taqa360\taqaNew19-10\edu-solutions-platform\database\seeders\Location\CityTypeServicePackagesSeeder.php

namespace Database\Seeders\Location;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CityTypeServicePackagesSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // -------------------------------------------------
        // Add a few links for the *original* city 1 (Riyadh)
        // so the admin can see both active & inactive rows there too.
        // We use type_service ids 1 (the original default) and 4 (the inactive one)
        // and package ids 1 (default) and 2 (basic).
        // firstOrCreate will keep existing row (city 1, ts 1, pkg 1) untouched.
        // -------------------------------------------------
        $tsIds = [1, 4];
        $pkgIds = [1, 2];

        foreach ($tsIds as $tsId) {
            foreach ($pkgIds as $pkgId) {
                // commission & discount are taken from the package row,
                // we add a small random override just to show variety.
                $commission = rand(5, 15);
                $discount   = rand(0, 10);

                // idempotent: insert only if this exact combination does not exist
                $exists = DB::table('city_type_service_packages')
                    ->where('city_id', 1)
                    ->where('type_service_id', $tsId)
                    ->where('package_id', $pkgId)
                    ->exists();

                if (! $exists) {
                    DB::table('city_type_service_packages')->insert([
                        'city_id'      => 1,
                        'type_service_id' => $tsId,
                        'package_id'   => $pkgId,
                        'commission'    => $commission,
                        'discount_rate' => $discount,
                        'desc'          => null,
                        'status'        => rand(0,1) ? 'active' : 'inactive',
                    ]);
                }
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}