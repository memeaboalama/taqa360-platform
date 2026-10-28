<?php

namespace App\Domain\Admin\Actions\Dashboard\Nationality;

use App\Domain\Admin\Repositories\NationalityRepository;

class FetchNationalityAction
{
    protected NationalityRepository $nationalityRepository;


    public function __construct(NationalityRepository $nationalityRepository)
    {
        $this->nationalityRepository = $nationalityRepository;
    }


    public function handle(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->nationalityRepository->all();
    }

}

