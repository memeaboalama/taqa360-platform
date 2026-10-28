<?php

namespace App\Domain\AppBase\Actions\Days;

use App\Domain\AppBase\Repositories\DaysRepository;

class FetchDays
{

    protected DaysRepository $daysRepository;


    public function __construct(DaysRepository $daysRepository)
    {
        $this->daysRepository = $daysRepository;
    }


    public function handle()
    {
        return $this->daysRepository->all();
    }
}
