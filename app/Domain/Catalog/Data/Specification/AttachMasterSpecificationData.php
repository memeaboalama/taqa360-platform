<?php

namespace App\Domain\Catalog\Data\Specification;

use Spatie\LaravelData\Data;

class AttachMasterSpecificationData extends Data
{
    public function __construct(
        public readonly int $item_id,
        public readonly int $master_specification_id,
    ) {}

    public static function rules(): array
    {
        return [
            'item_id' => ['required', 'exists:items,id'],
            'master_specification_id' => [
                'required',
                // 'exists:specifications,id,item_id,NULL' // Rule to ensure it's a master spec
                'exists:specifications,id' // Rule to ensure it's a master spec
            ],
        ];
    }
}
