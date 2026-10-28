<?php

namespace Database\Seeders\AppBase;

use App\Domain\AppBase\Models\Bank;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('banks')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            [
                'title'  =>  [
                    'ar'    =>  'bank1',
                    'en'    =>  'bank1',
                ],


            ],
            [
                'title'  =>  [
                    'ar'    =>  'bank2',
                    'en'    =>  'bank2',
                ],


            ],
            [
                'title'  =>  [
                    'ar'    =>  'bank3',
                    'en'    =>  'bank3',
                ],


            ],

        ];
        foreach ($data as $record)
            Bank::create([
                'title'=>$record["title"],
            ]);
    }
}
