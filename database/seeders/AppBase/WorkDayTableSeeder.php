<?php

namespace Database\Seeders\AppBase;

use App\Support\Enum\StatusEnum;
use Domain\MarketPlace\Models\Day;
use Domain\MarketPlace\Models\MerchantBranch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class WorkDayTableSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('merchant_branches_work_days')->truncate();
        DB::table('merchant_branches_work_day_hours')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        Model::unguard();


        foreach (MerchantBranch::all() as $value) {
            foreach (Day::all() as $day) {
                $work_day = $value->workDays()->create([
                    'day_id' => $day->id,
                    'status' => StatusEnum::ACTIVE->value,
                ]);
                $work_day->workHours()->create([
                    'merchant_branch_id' => $value->id,
                    'status' => StatusEnum::ACTIVE->value,
                    'from' => "08:00:00",
                    'to' => "23:00:00",
                ]);
            }


        }
    }
}
