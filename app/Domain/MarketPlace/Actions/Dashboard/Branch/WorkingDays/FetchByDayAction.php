<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Branch\WorkingDays;

use App\Domain\MarketPlace\Repositories\Branch\WorkingDays\WorkingDaysRepository;

class FetchByDayAction
{
    protected WorkingDaysRepository $workingDaysRepository;


    public function __construct(WorkingDaysRepository $workingDaysRepository)
    {
        $this->workingDaysRepository = $workingDaysRepository;
    }


    public function handle(array $filters)
    {
        return $this->workingDaysRepository->getMerchantBranchByDay($filters);
    }
}
