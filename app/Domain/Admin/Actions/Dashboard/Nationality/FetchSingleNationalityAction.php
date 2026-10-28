<?php

namespace App\Domain\Admin\Actions\Dashboard\Nationality;

use App\Domain\Admin\Repositories\NationalityRepository;

class FetchSingleNationalityAction
{
    protected NationalityRepository $nationalityRepository;


    public function __construct(NationalityRepository $nationalityRepository)
    {
        $this->nationalityRepository = $nationalityRepository;
    }


    public function handle(int $id): \Illuminate\Database\Eloquent\Model|null
    {
        return $this->nationalityRepository->findByCols(['id' => $id]);
    }

}

