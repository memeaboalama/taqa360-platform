<?php

namespace App\Domain\Admin\Actions\Dashboard\Educator;

use Domain\Educator\Repositories\EducatorRepository;
use App\Support\Enum\StatusEnum;

class DestroyEducatorAction
{
    protected EducatorRepository $educatorRepository;


    public function __construct(EducatorRepository $educatorRepository)
    {
        $this->educatorRepository = $educatorRepository;
    }


    public function handle(int $id): bool
    {
        return $this->educatorRepository->findByCols(['id' => $id])->delete();
    }
}
