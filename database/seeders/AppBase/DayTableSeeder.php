<?php

namespace Database\Seeders\AppBase;

use Domain\MarketPlace\Models\Day;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;


class DayTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Day::truncate();
        Schema::enableForeignKeyConstraints();

        Model::unguard();

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
        foreach ($data as $record)
            Day::updateOrCreate($record);
    }
}
