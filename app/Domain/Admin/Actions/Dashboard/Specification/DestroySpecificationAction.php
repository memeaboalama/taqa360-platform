<?php

namespace App\Domain\Admin\Actions\Dashboard\Specification;

use App\Domain\AppBase\Repositories\SpecificationRepository;

class DestroySpecificationAction
{
    protected SpecificationRepository $specificationRepository;


    public function __construct(SpecificationRepository $specificationRepository)
    {
        $this->specificationRepository = $specificationRepository;
    }


    public function handle(int $id): bool
    {
        return $this->specificationRepository->destroy($id);
    }
}
