<?php

namespace App\Domain\Catalog\Data\SpecificationProperty;


use App\Domain\Catalog\Data\Specification\SpecificationData;
use App\Domain\Catalog\Data\Item\ItemData;
use App\Domain\Catalog\Models\SpecificationProperty;
use App\Support\Enum\StatusEnum;

use DateTime;
use Spatie\LaravelData\Attributes\AutoLazy;
use Spatie\LaravelData\Data;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\LoadRelation;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

class SpecificationPropertyData extends Data
{




        public Lazy|int $id;
        public Lazy|string $code;
        public Lazy|string $name;
        public Lazy|string $value;
        #[WithCast(EnumCast::class, type: StatusEnum::class)]
        public Lazy|StatusEnum $status;
        public Lazy|array $meta;
        public Lazy|int $specification_id;
        public Lazy|int $item_id;
        #[LoadRelation]
        #[AutoLazy]
        // #[AutoWhenLoadedLazy('specification')]
        public Lazy|SpecificationData $specification;
        #[LoadRelation]
        #[AutoLazy]
        // #[AutoWhenLoadedLazy('item')]
        public Lazy|ItemData $item;
        #[WithCast(DateTimeInterfaceCast::class)]
        public Lazy|DateTime $created_at;
        #[WithCast(DateTimeInterfaceCast::class)]
        public Lazy|DateTime $update_at;
        
    
    


    public function includeProperties(): array
    {
        $arr= [
            'id',
            'code',
            'name',
            'value',
            'status',
            'meta',
            'specification_id',
            'item_id',
            
        ];

    return $arr;
    }
    public function exceptProperties(): array
    {
        $arr= [
            
        ];
        if(!isset($this->item_id) || !is_int($this->item_id) || $this->item_id == 0)
        $arr[]='item_id';
        if(!isset($this->meta) || !is_array($this->meta) || empty($this->meta))
        $arr[]='meta';
        if(!isset($this->code)||is_null($this->code))
        $arr[]='code';
        if(!isset($this->value)||is_null($this->value))
        $arr[]='value';
        
    return $arr;
    }
}
