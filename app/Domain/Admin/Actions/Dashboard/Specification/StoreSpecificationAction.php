<?php

namespace App\Domain\Admin\Actions\Dashboard\Specification;

use App\Domain\AppBase\Repositories\SpecificationRepository;

class StoreSpecificationAction
{
    protected SpecificationRepository $specificationRepository;


    public function __construct(SpecificationRepository $specificationRepository)
    {
        $this->specificationRepository = $specificationRepository;
    }

    public function handle(array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        $categoryIds = $data['category_ids'];
        unset($data['category_ids']);

        $model= $this->specificationRepository->store($data);
        $model->categories()->sync($categoryIds);
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
