<?php

namespace App\Domain\Educator\Actions;

use Domain\Educator\Models\Educator;
use Domain\Educator\Repositories\EducatorRepository;
use Illuminate\Database\Eloquent\Collection;

class FetchSingleEducatorAction
{
    protected EducatorRepository $educatorRepository;


    public function __construct(EducatorRepository $educatorRepository)
    {
        $this->educatorRepository = $educatorRepository;
    }

    public function handle(int $id): \Illuminate\Database\Eloquent\Model|null
    {
        return $this->educatorRepository->findById($id);
    }
}
