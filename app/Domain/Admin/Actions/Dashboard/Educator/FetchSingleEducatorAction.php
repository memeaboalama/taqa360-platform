<?php

namespace App\Domain\Admin\Actions\Dashboard\Educator;

use Domain\Educator\Repositories\EducatorRepository;


class FetchSingleEducatorAction
{
    protected EducatorRepository $educatorRepository;


    public function __construct(EducatorRepository $educatorRepository)
    {
        $this->educatorRepository = $educatorRepository;
    }

    public function handle(int $id): \Illuminate\Database\Eloquent\Model|null
    {
        return $this->educatorRepository->findByCols(['id' => $id]);
    }
}
