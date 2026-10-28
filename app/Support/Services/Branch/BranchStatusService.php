<?php

namespace App\Support\Services\Branch;


use App\Domain\MarketPlace\Models\MerchantOrder;
use Carbon\Carbon;

class BranchStatusService
{


    public function slots($branch, $data): bool|array
    {
        $gt_today = Carbon::parse($data["date"])->gt(today());

        $initial_time = [];

        $slot = [];

        $time_off_arr_start = [];

        ////////////////////////////////////
        ///
        $work_day = $this->getWorkDay($branch, $data);

        if (!$work_day) return false;

        $branch_setting_minutes = $this->get_setting_minutes($branch);

        ////////////////////////////////////


        $time_off = $this->get_orders_in_same_date($branch, $data);

        foreach ($time_off as $off) $time_off_arr_start[] = Carbon::parse($off->from)->format('h:i A');


        if ($branch->accept_multi_requests_at_same_time) $time_off_arr_start = $this->handle_times_depend_on_order_count($time_off_arr_start, $branch, $data);

        ////////////////////////////////////

        foreach ($work_day?->workHours as $work_hour) {


            $from_parsed = Carbon::parse($work_hour->from);


            $count_working_hours = $from_parsed->diffInMinutes(Carbon::parse($work_hour->to)) / $branch_setting_minutes;

            if ($gt_today || $from_parsed->gte(now()->addHours(2))) {

                $initial_time[] = $from_parsed->format('h:i A'); // here
            }

            $from = $from_parsed;
            for ($i = 1; $i < $count_working_hours; $i++) {

                if ($from->addMinutes($branch_setting_minutes)->gte(now()->addHours(2)) || $gt_today)

                    $slot[] = $from->format('h:i A'); // here
            }

        }


        ////////////////////////////////////

        $times = array_merge($initial_time, $slot);

        $all_time = [];

        foreach ($times as $time) {
            $new_time = [
                'time' => $time,
                'available' => in_array($time, $time_off_arr_start) ? false : true,
            ];

            $all_time[] = $new_time;
        }

        return $all_time;

    }

    public function getWorkDay($branch, $data)
    {
        Carbon::setLocale('en');
        $work_day = $branch->workDays()
            ->active()
            ->whereHas('day', function ($q) use ($data) {
                return $q->whereJsonContains('name', ['en' => Carbon::parse($data["date"])->dayName]);
            })
            ->first();
        if (!$work_day || $work_day->workHours()?->get()?->isEmpty()) return false;

        return $work_day;
    }

    public function get_setting_minutes($branch): int
    {
        return $branch?->slot ?? 60;
    }

    public function get_orders_in_same_date($branch, $data)
    {

        return MerchantOrder::where('merchant_id', $data["merchant"])
            ->where('merchant_branch_id', $branch->id)
            ->whereDate('date', Carbon::parse($data["date"]))
            ->when($data["date"] == today()->toDateString(), function ($q) {
                $q->whereTime('from', '>', now()->toTimeString());
//                    ->whereTime('finish_time', '>', now()->toTimeString());
            })
            ->get();
    }

    public function handle_times_depend_on_order_count($time_off_arr_start, $branch, $data)
    {

        foreach ($time_off_arr_start as $k => $value) {
            $count = MerchantOrder::where('merchant_id', $data["merchant"])
                ->where('merchant_branch_id', $branch->id)
                ->whereDate('date', $data["date"])
                ->whereTime('from', Carbon::parse($value))
                ->count();


            if ($count < $branch->requests_per_hour_for_same_service) {
                unset($time_off_arr_start[$k]);
            }
        }

        return $time_off_arr_start;
    }


}
