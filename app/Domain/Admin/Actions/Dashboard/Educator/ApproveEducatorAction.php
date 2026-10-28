<?php

namespace App\Domain\Admin\Actions\Dashboard\Educator;

use Domain\Educator\Repositories\EducatorRepository;

class ApproveEducatorAction
{
    protected EducatorRepository $educatorRepository;

    public function __construct(EducatorRepository $educatorRepository)
    {
        $this->educatorRepository = $educatorRepository;
    }

    public function handle(int $id): bool
    {
        $educator = $this->educatorRepository->findByCols(['id' => $id]);
        if ($educator) {
            $educator->is_accepted = true;
            return $educator->save();
        }
        return false;
    }
}
