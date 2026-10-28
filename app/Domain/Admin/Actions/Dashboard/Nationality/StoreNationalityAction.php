<?php

namespace App\Domain\Admin\Actions\Dashboard\Nationality;
use App\Domain\Admin\Repositories\NationalityRepository;

class StoreNationalityAction
{
    protected NationalityRepository $nationalityRepository;


    public function __construct(NationalityRepository $nationalityRepository)
    {
        $this->nationalityRepository = $nationalityRepository;
    }


    public function handle(array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->nationalityRepository->store($data);
    }

}

