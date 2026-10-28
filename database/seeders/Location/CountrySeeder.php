<?php

namespace Database\Seeders\Location;

use App\Domain\AppBase\Models\Location\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('countries')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $countries = [
            ['ar' => 'المملكة العربية السعودية', 'en' => 'Saudi Arabia', 'iso' => 'sa', 'currency_id' => 1, 'vat' => 15],
            ['ar' => 'الإمارات العربية المتحدة', 'en' => 'United Arab Emirates', 'iso' => 'ae', 'currency_id' => 1, 'vat' => 5],
            ['ar' => 'قطر', 'en' => 'Qatar', 'iso' => 'qa', 'currency_id' => 1, 'vat' => 0],
            ['ar' => 'الكويت', 'en' => 'Kuwait', 'iso' => 'kw', 'currency_id' => 1, 'vat' => 0],
            ['ar' => 'عمان', 'en' => 'Oman', 'iso' => 'om', 'currency_id' => 1, 'vat' => 0],
            ['ar' => 'البحرين', 'en' => 'Bahrain', 'iso' => 'bh', 'currency_id' => 1, 'vat' => 0],
            ['ar' => 'اليمن', 'en' => 'Yemen', 'iso' => 'ye', 'currency_id' => 1, 'vat' => 0],
            ['ar' => 'مصر', 'en' => 'Egypt', 'iso' => 'eg', 'currency_id' => 1, 'vat' => 0],
            ['ar' => 'الأردن', 'en' => 'Jordan', 'iso' => 'jo', 'currency_id' => 1, 'vat' => 0],
            ['ar' => 'لبنان', 'en' => 'Lebanon', 'iso' => 'lb', 'currency_id' => 1, 'vat' => 0],
            ['ar' => 'فلسطين', 'en' => 'Palestine', 'iso' => 'ps', 'currency_id' => 1, 'vat' => 0],
            ['ar' => 'سوريا', 'en' => 'Syria', 'iso' => 'sy', 'currency_id' => 1, 'vat' => 0],
            ['ar' => 'العراق', 'en' => 'Iraq', 'iso' => 'iq', 'currency_id' => 1, 'vat' => 0],
            ['ar' => 'السودان', 'en' => 'Sudan', 'iso' => 'sd', 'currency_id' => 1, 'vat' => 0],
            ['ar' => 'ليبيا', 'en' => 'Libya', 'iso' => 'ly', 'currency_id' => 1, 'vat' => 0],
            ['ar' => 'تونس', 'en' => 'Tunisia', 'iso' => 'tn', 'currency_id' => 1, 'vat' => 0],
            ['ar' => 'الجزائر', 'en' => 'Algeria', 'iso' => 'dz', 'currency_id' => 1, 'vat' => 0],
            ['ar' => 'المغرب', 'en' => 'Morocco', 'iso' => 'ma', 'currency_id' => 1, 'vat' => 0],
            ['ar' => 'موريتانيا', 'en' => 'Mauritania', 'iso' => 'mr', 'currency_id' => 1, 'vat' => 0],
            ['ar' => 'الصومال', 'en' => 'Somalia', 'iso' => 'so', 'currency_id' => 1, 'vat' => 0],
            ['ar' => 'جيبوتي', 'en' => 'Djibouti', 'iso' => 'dj', 'currency_id' => 1, 'vat' => 0],
            ['ar' => 'جزر القمر', 'en' => 'Comoros', 'iso' => 'km', 'currency_id' => 1, 'vat' => 0],
        ];

        foreach ($countries as $c) {
            Country::create([
                'title' => ['ar' => $c['ar'], 'en' => $c['en']],
                'iso' => $c['iso'],
                'currency_id' => $c['currency_id'],
                'vat' => $c['vat'],
            ]);
        }
    }
}
