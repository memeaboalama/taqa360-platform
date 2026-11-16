<?php

namespace Database\Seeders\AppBase;

use Domain\MarketPlace\Models\Day;
use Illuminate\Database\Seeder;


class DayTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name'  =>  [
                    'ar'    =>  'السبت',
                    'en'    =>  'Saturday',
                ],

            ],
            [
                'name'  =>  [
                    'ar'    =>  'الأحد',
                    'en'    =>  'Sunday',
                ],

            ],
            [
                'name'  =>  [
                    'ar'    =>  'الإثنين',
                    'en'    =>  'Monday',
                ],

            ],
            [
                'name'  =>  [
                    'ar'    =>  'الثلاثاء',
                    'en'    =>  'Tuesday',
                ],

            ],
            [
                'name'  =>  [
                    'ar'    =>  'الاربعاء',
                    'en'    =>  'Wednesday',
                ],

            ],
            [
                'name'  =>  [
                    'ar'    =>  'الخميس',
                    'en'    =>  'Thursday',
                ],

            ],
            [
                'name'  =>  [
                    'ar'    =>  'الجمعه',
                    'en'    =>  'Friday',
                ],

            ],
        ];
        $existingDays = Day::query()->get();

        foreach ($data as $record) {
            $day = $existingDays->first(
                fn (Day $day) => $day->getTranslation('name', 'en') === $record['name']['en']
            );

            if ($day) {
                $day->setTranslations('name', $record['name']);
                $day->save();
                continue;
            }

            $existingDays->push(Day::create($record));
        }
    }
}
