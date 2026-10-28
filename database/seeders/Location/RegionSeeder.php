<?php

namespace Database\Seeders\Location;

use App\Domain\AppBase\Models\Location\City;
use App\Domain\AppBase\Models\Location\Region;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Create a region for each city that has no region yet, then link
     * each city to its matching region (matched by country + key).
     *
     * Idempotent: safe to run multiple times.
     */
    public function run(): void
    {
        City::query()
            ->whereNotNull('key')
            ->get()
            ->each(function (City $city) {
                $region = Region::query()->firstOrCreate(
                    ['key' => $city->key, 'country_id' => $city->country_id],
                    [
                        'title' => $city->getTranslations('title'),
                        'status' => 'active',
                        'priority' => $city->priority ?? 1,
                    ]
                );

                if (! $city->region_id) {
                    $city->region_id = $region->id;
                    $city->save();
                }
            });
    }
}