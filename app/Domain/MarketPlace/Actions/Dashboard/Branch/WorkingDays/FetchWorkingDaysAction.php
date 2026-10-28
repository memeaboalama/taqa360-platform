<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Branch\WorkingDays;

use App\Domain\MarketPlace\Repositories\Branch\WorkingDays\WorkingDaysRepository;

class FetchWorkingDaysAction
{
    protected WorkingDaysRepository $workingDaysRepository;


    public function __construct(WorkingDaysRepository $workingDaysRepository)
    {
        $this->workingDaysRepository = $workingDaysRepository;
    }


    public function handle(int $branch_id): \Illuminate\Database\Eloquent\Collection|array
    {
        return $this->workingDaysRepository->getMerchantBranchWorkingDays($branch_id);
    }
}
