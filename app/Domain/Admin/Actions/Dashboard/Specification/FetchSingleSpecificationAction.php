<?php

namespace App\Domain\Admin\Actions\Dashboard\Specification;

use App\Domain\AppBase\Repositories\SpecificationRepository;

class FetchSingleSpecificationAction
{
    protected SpecificationRepository $specificationRepository;


    public function __construct(SpecificationRepository $specificationRepository)
    {
        $this->specificationRepository = $specificationRepository;
    }


    public function handle(int $id): \Illuminate\Database\Eloquent\Model|null
    {
        return $this->specificationRepository->findByCols(['id' => $id]);
    }
}
