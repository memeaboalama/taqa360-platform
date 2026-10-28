<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Branch\WorkingDays;

use App\Domain\MarketPlace\Repositories\Branch\WorkingDays\WorkingDaysRepository;

class DeleteWorkingDayHourAction
{
    protected WorkingDaysRepository $workingDaysRepository;


    public function __construct(WorkingDaysRepository $workingDaysRepository)
    {
        $this->workingDaysRepository = $workingDaysRepository;
    }

    public function handle(int $branch_id, int $day_id, int $hour_id)
    {
        $workingDay = $this->workingDaysRepository
            ->getMerchantBranchByDay(['day_id'=> $day_id, 'merchant_branch_id'=> $branch_id]);

        if ($workingDay && $workingDay->workHours->count() > 0){
            $workingDay->workHours()->where('id', $hour_id)->delete();
        }

        return "";
    }
}
