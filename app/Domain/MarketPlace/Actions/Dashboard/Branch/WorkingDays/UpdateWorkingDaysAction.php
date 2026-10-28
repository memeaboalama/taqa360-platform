<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Branch\WorkingDays;

use App\Domain\MarketPlace\Repositories\Branch\WorkingDays\WorkingDaysRepository;

class UpdateWorkingDaysAction
{
    protected WorkingDaysRepository $workingDaysRepository;


    public function __construct(WorkingDaysRepository $workingDaysRepository)
    {
        $this->workingDaysRepository = $workingDaysRepository;
    }


    public function handle(int $branch_id, array $data)
    {
        if (isset($data['items']))
        {
            foreach ($data['items'] as $key => $day)
            {
                $workingDay = $this->workingDaysRepository
                    ->getMerchantBranchByDay(['day_id'=> $day['id'], 'merchant_branch_id'=> $branch_id]);

                if (!$workingDay){
                    $workingDay = $this->workingDaysRepository->store([
                        'merchant_branch_id' => $branch_id,
                        'day_id' => $day['id'],
                        'status' => isset($day['status']) ? $day['status'] : "inactive",
                    ]);
                }
                $workingDay->update(['status' => isset($day['status']) ? $day['status'] : "inactive"]);
                if ($workingDay->workHours->count() > 0)
                {
                    $workingDay->workHours()->delete();

                }
                if (isset($day['hours']))
                {
                    foreach ($day['hours'] as $key => $value)
                    {
                        $workingDay->workHours()->create(array_merge([
                            'merchant_branch_id' => $branch_id,
                        ], $value));
                    }
                }
            }
        }
        return "";
    }
}
