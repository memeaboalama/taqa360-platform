<?php

namespace App\Domain\Catalog\Actions\Specification;

use App\Domain\Catalog\Models\Specification;
use App\Domain\Catalog\Data\Specification\AttachMasterSpecificationData;
// use App\Domain\Catalog\Models\Item;
use App\Support\Enum\StatusEnum;
use Domain\MarketPlace\Models\Item;
use Illuminate\Support\Facades\DB;


class AttachMasterSpecificationToItemAction
{
    /**
     * Attaches a master specification to an item by creating a deep copy.
     */
    public function execute(AttachMasterSpecificationData $data): Specification
    {
        $masterSpec = Specification::isMasterOrNotInItem($data->item_id)->with('properties')->findOrFail($data->master_specification_id);
        $item = Item::findOrFail($data->item_id);

        return DB::transaction(function () use ($masterSpec, $item) {
            // 1. Replicate the Specification itself
            $itemSpecification = $masterSpec->replicate(['item_id']);
            $itemSpecification->item_id = $item->id;
            $meta = [...($itemSpecification->meta ?? [])];
            $meta['merchant_id'] = $item->merchant_id;
            if ($masterSpec->isMaster) {
                // $meta['specification_id']= $masterSpec->id;
                $meta['master_specification_id'] = $masterSpec->id;
            }
            $itemSpecification->meta = $meta;
            $itemSpecification->save();

            // 2. Prepare properties for the new specification
            $propertiesToCreate = [];
            foreach ($masterSpec->properties as $property) {

                // Replicate each property, removing original IDs
                $newProperty = $property->replicate(['specification_id', 'item_id']);
                $newProperty->item_id = $item->id;
                $property_meta = [...($property->meta ?? [])];
                $property_meta['merchant_id'] = $item->merchant_id;
                if ($masterSpec->isMaster) {
                    // $property_meta['specification_id']= $masterSpec->id;
                    // $property_meta['property_id']= $property->id;
                    $property_meta['master_specification_id'] = $masterSpec->id;
                    $property_meta['master_property_id'] = $property->id;
                }
                $newProperty->meta = $property_meta;
                $propertiesToCreate[] = $newProperty;
            }

            // 3. Create all properties for the new Item Specification
            if (!empty($propertiesToCreate)) {
                $itemSpecification->properties()->saveMany($propertiesToCreate);
            }

            return $itemSpecification;
        });
    }
}
