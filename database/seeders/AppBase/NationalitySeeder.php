<?php

namespace Database\Seeders\AppBase;

use App\Domain\AppBase\Models\Nationality\Nationality;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NationalitySeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('nationalities')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $nationalities = [
            ['ar' => 'سعودي',     'en' => 'Saudi'],
            ['ar' => 'إماراتي',   'en' => 'Emirati'],
            ['ar' => 'قطري',      'en' => 'Qatari'],
            ['ar' => 'بحريني',    'en' => 'Bahraini'],
            ['ar' => 'كويتي',     'en' => 'Kuwaiti'],
            ['ar' => 'عُماني',    'en' => 'Omani'],
            ['ar' => 'يمني',      'en' => 'Yemeni'],
            ['ar' => 'مصري',      'en' => 'Egyptian'],
            ['ar' => 'سوداني',    'en' => 'Sudanese'],
            ['ar' => 'ليبي',      'en' => 'Libyan'],
            ['ar' => 'تونسي',     'en' => 'Tunisian'],
            ['ar' => 'جزائري',    'en' => 'Algerian'],
            ['ar' => 'مغربي',     'en' => 'Moroccan'],
            ['ar' => 'موريتاني',  'en' => 'Mauritanian'],
            ['ar' => 'جيبوتي',    'en' => 'Djiboutian'],
            ['ar' => 'صومالي',    'en' => 'Somali'],
            ['ar' => 'فلسطيني',   'en' => 'Palestinian'],
            ['ar' => 'أردني',     'en' => 'Jordanian'],
            ['ar' => 'لبناني',    'en' => 'Lebanese'],
            ['ar' => 'سوري',      'en' => 'Syrian'],
            ['ar' => 'عراقي',     'en' => 'Iraqi'],
            ['ar' => 'كوموري',    'en' => 'Comorian'],
        ];

        foreach ($nationalities as $nat) {
            Nationality::create([
                'title'  => [
                    'ar' => $nat['ar'],
                    'en' => $nat['en'],
                ],
                'status' => 'active',
            ]);
        }
    }
}
