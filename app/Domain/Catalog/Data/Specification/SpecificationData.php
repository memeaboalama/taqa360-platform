<?php

namespace App\Domain\Catalog\Data\Specification;


use App\Domain\Catalog\Data\SpecificationProperty\SpecificationPropertyData;
use App\Domain\Catalog\Data\Item\ItemData;
use App\Domain\Catalog\Models\Specification;
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

class SpecificationData extends Data
{

    // public float $amount=0;


        // public Lazy|string $type,
        public Lazy|int $id;
        public Lazy|string $code;
        public Lazy|int $item_id;
        public Lazy|string $name;
        public Lazy|string $description;
        #[WithCast(EnumCast::class, type: StatusEnum::class)]
        public Lazy|StatusEnum $status;
        public Lazy|array $meta;
        // #[LoadRelation]
        // #[AutoLazy]
        // // #[AutoWhenLoadedLazy('item')]
        // public Lazy|ItemData $item;
        #[WithCast(DateTimeInterfaceCast::class)]
        public Lazy|DateTime $created_at;
        // #[WithCast(DateTimeInterfaceCast::class)]
        // public Lazy|DateTime $update_at;
        

        /** @var array<SpecificationPropertyData>  */
        /** @var Collection<int, SpecificationPropertyData> */
        #[LoadRelation]
        #[MapOutputName("properties")]
        #[AutoLazy]
        #[DataCollectionOf(SpecificationPropertyData::class)]
        public Lazy|DataCollection|array $properties;
    
    


    public function includeProperties(): array
    {
        $arr= [
            'id',
            'code',
            'name',
            'description',
            'status',
            'meta',
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
        if(!isset($this->description)||is_null($this->description))
        $arr[]='description';
        
    return $arr;
    }
}
