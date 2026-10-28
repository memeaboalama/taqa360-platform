<?php

namespace App\Domain\Admin\Actions\Dashboard\Specification;

use App\Domain\AppBase\Repositories\SpecificationRepository;
use Illuminate\Support\Facades\Log;

class StoreSpecificationAction
{
    protected SpecificationRepository $specificationRepository;


    public function __construct(SpecificationRepository $specificationRepository)
    {
        $this->specificationRepository = $specificationRepository;
    }

    public function handle(array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
         Log::debug("handle");
        $model= $this->specificationRepository->store($data);
        foreach ($data['specifications_properties'] as $option)
        {
            // $model->specifications_properties()->firstOrCreate( ['name' => $option['name']],
            // [
            //     'name' => $option['name'],
            //     'status' => $option['status'],
            // ]);
           $model->specifications_properties()->create( ['name' => $option['name'], 'status' => $option['status']]);
            // Log::debug("Specifications Properties: ", ['model' => $model]);
        }
        return $model;
    }
}
