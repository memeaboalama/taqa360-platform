<?php

namespace App\Domain\Admin\Actions\Dashboard\Nationality;


use App\Domain\Admin\Repositories\NationalityRepository;

class DestroyNationalityAction
{
    protected NationalityRepository $nationalityRepository;


    public function __construct(NationalityRepository $nationalityRepository)
    {
        $this->nationalityRepository = $nationalityRepository;
    }


    public function handle(int $id): bool
    {
        return $this->nationalityRepository->destroy($id);
    }

}

