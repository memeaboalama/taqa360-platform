<?php
// D:\Newtouch\Projects\taqa360\taqaNew19-10\edu-solutions-platform\database\seeders\Location\CountriesAndCitiesSeeder.php

namespace Database\Seeders\Location;

use App\Domain\AppBase\Models\Location\Country;
use App\Domain\AppBase\Models\Location\City;
use App\Domain\AppBase\Models\Location\Region;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountriesAndCitiesSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // -------------------------------------------------
        // 5 new countries with iso codes not present originally
        // -------------------------------------------------
        $newCountries = [
            ['ar' => 'بحرين', 'en' => 'Bahrain', 'iso' => 'bh'],
            ['ar' => 'عمان', 'en' => 'Oman', 'iso' => 'om'],
            ['ar' => 'قطر', 'en' => 'Qatar', 'iso' => 'qa'],
            ['ar' => 'كويت', 'en' => 'Kuwait', 'iso' => 'kw'],
            ['ar' => 'الإمارات', 'en' => 'UAE', 'iso' => 'ae'], // iso 'ae' already exists; firstOrCreate will keep existing
        ];

        // map iso -> arabic display name for later use
        $countryArName = [];
        foreach ($newCountries as $c) {
            Country::firstOrCreate(
                ['iso' => $c['iso']],
                [
                    'title' => ['ar' => $c['ar'], 'en' => $c['en']],
                    'iso' => $c['iso'],
                    'status' => 'active',
                    'priority' => 1,
                ]
            );
            $countryArName[$c['iso']] = $c['ar'];
        }

        // -------------------------------------------------
        // Get the country objects we just (or already) created
        // -------------------------------------------------
        $countryObjs = Country::whereIn('iso', array_keys($countryArName))->get();

        // -------------------------------------------------
        // 3 regions per country
        // -------------------------------------------------
        foreach ($countryObjs as $country) {
            $arCountry = $countryArName[$country->iso] ?? 'Country';
            for ($i = 1; $i <= 3; $i++) {
                $regionKey = "reg_{$i}_{$country->iso}";
                Region::firstOrCreate(
                    ['key' => $regionKey, 'country_id' => $country->id],
                    [
                        'title' => 'منطقة ' . $i . ' ' . $arCountry,
                        'status' => 'active',
                        'priority' => 1,
                        'country_id' => $country->id,
                    ]
                );
            }
        }

        // -------------------------------------------------
        // 3 cities per region
        // -------------------------------------------------
        $regions = Region::whereIn('country_id', $countryObjs->pluck('id'))->get();
        foreach ($regions as $region) {
            $arRegion = 'منطقة'; // we can embed country name if needed, but keep simple
            for ($i = 1; $i <= 3; $i++) {
                $cityTitleAr = 'مدينة ' . $i . ' - ' . $arRegion;
                $cityTitleEn = 'City ' . $i . ' - Region';
                City::firstOrCreate(
                    ['title' => ['ar' => $cityTitleAr, 'en' => $cityTitleEn], 'country_id' => $region->country_id],
                    [
                        'key' => $cityTitleAr,
                        'region_id' => $region->id,
                        'status' => ($i % 2 === 0 ? 'inactive' : 'active'),
                        'priority' => $i,
                    ]
                );
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}