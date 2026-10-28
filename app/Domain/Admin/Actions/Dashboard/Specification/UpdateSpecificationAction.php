<?php

namespace App\Domain\Admin\Actions\Dashboard\Specification;

use App\Domain\AppBase\Repositories\SpecificationRepository;

class UpdateSpecificationAction
{
    protected SpecificationRepository $specificationRepository;


    public function __construct(SpecificationRepository $specificationRepository)
    {
        $this->specificationRepository = $specificationRepository;
    }

    public function handle(int $id, array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        $properties = $data['specifications_properties'] ?? [];
        unset($data['specifications_properties']);

        $model = $this->specificationRepository->update($id, $data);

        $model->specifications_properties()->delete();
        foreach ($properties as $option) {
            $model->specifications_properties()->create([
                'name' => $option['name'],
                'status' => $option['status'],
            ]);
        }

        return $model;
    }
}
