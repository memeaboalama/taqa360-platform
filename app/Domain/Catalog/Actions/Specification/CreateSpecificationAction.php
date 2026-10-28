<?php

namespace App\Domain\Catalog\Actions\Specification;

use App\Domain\Catalog\Models\Specification;
use App\Domain\Catalog\Data\Specification\CreateSpecificationData;
use App\Support\Enum\StatusEnum;
use Illuminate\Support\Facades\DB;


class CreateSpecificationAction
{
    public function execute(CreateSpecificationData $data): Specification
    {
        return DB::transaction(function () use ($data) {
            $specification = Specification::create([
                'item_id' => $data->item_id,
                'code' => $data->code,
                'name' => $data->name,
                'description' => $data->description,
                'status' => $data->status??StatusEnum::ACTIVE,
                'meta'=> $data->meta??[],
            ]);

            if (!empty($data->properties)) {
                // 2. Prepare properties for the new specification
            $propertiesToCreate = [];
            foreach ($data->properties as $property) {
                // Replicate each property, removing original IDs
                $newProperty = $property;
                if(isset($data->item_id)&&$data->item_id)
                $newProperty['item_id'] = $data->item_id;
                if(!isset($newProperty['meta'])||!is_array($newProperty['meta']))
                $newProperty['meta'] = [];
                $propertiesToCreate[] = $newProperty;
            }
                // $specification->properties()->createMany($data->properties);
                $specification->properties()->createMany($propertiesToCreate);
            }

            return $specification;
        });
    }
}