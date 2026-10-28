<?php

namespace App\Domain\Catalog\Actions\Specification;

use App\Domain\Catalog\Models\Specification;
use App\Domain\Catalog\Data\Specification\UpdateSpecificationRequest;
use Illuminate\Support\Facades\DB;


class UpdateSpecificationAction
{
    public function execute(Specification $specification, UpdateSpecificationRequest $data): Specification
    {
        return DB::transaction(function () use ($specification, $data) {
            $specification->update([
                'name' => $data->name,
                'code' => $data->code??$specification->code,
                'description' => $data->description,
                'status' => $data->status,
                'item_id' => $data->item_id??=$specification->item_id,
            ]);

            if (!empty($data->properties)) {
            // 2. Prepare properties for the new specification
            $propertiesToCreate = [];
            foreach ($data->properties as $property) {
                // Replicate each property, removing original IDs
                $newProperty = $property;
                if(isset($data->item_id)&&$data->item_id)
                $newProperty['item_id'] = $data->item_id??=$specification->item_id;
                if(!isset($newProperty['meta'])||!is_array($newProperty['meta']))
                $newProperty['meta'] = [];
                $propertiesToCreate[] = $newProperty;
            }
            // Simple handling: remove old properties and add new ones
                $specification->properties()->delete();
                // $specification->properties()->createMany($data->properties);
                $specification->properties()->createMany($propertiesToCreate);
            }
            
            return $specification->fresh('properties');
        });
    }
}