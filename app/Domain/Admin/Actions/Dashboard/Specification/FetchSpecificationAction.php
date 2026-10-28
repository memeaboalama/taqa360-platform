<?php

namespace App\Domain\Admin\Actions\Dashboard\Specification;

use App\Domain\AppBase\Repositories\SpecificationRepository;
use App\Support\Enum\StatusEnum;
use Illuminate\Database\Eloquent\Collection;

class FetchSpecificationAction
{
    protected SpecificationRepository $specificationRepository;


    public function __construct(SpecificationRepository $specificationRepository)
    {
        $this->specificationRepository = $specificationRepository;
    }

    public function handle(): Collection
    {
        return $this->specificationRepository->all(condition: ['status' => StatusEnum::ACTIVE->value]);
    }
}
