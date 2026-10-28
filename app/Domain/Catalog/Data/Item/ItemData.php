<?php

namespace App\Domain\Catalog\Data\Item;


use App\Domain\Catalog\Data\SpecificationProperty\SpecificationPropertyData;
use App\Domain\Catalog\Data\Specification\SpecificationData;
use App\Domain\Catalog\Models\Item;
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

class ItemData extends Data
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
        
        #[WithCast(DateTimeInterfaceCast::class)]
        public Lazy|DateTime $created_at;
        #[WithCast(DateTimeInterfaceCast::class)]
        public Lazy|DateTime $update_at;
        

        /** @var array<SpecificationData>  */
        /** @var Collection<int, SpecificationData> */
        #[LoadRelation]
        #[MapOutputName("specifications")]
        #[AutoLazy]
        #[DataCollectionOf(SpecificationData::class)]
        // public Lazy|DataCollection|array $specifications;
        public Lazy|DataCollection|array $itemSpecifications;



        /** @var array<SpecificationPropertyData>  */
        /** @var Collection<int, SpecificationPropertyData> */
        #[LoadRelation]
        #[MapOutputName("specification_properties")]
        #[AutoLazy]
        #[DataCollectionOf(SpecificationPropertyData::class)]
        // public Lazy|DataCollection|array $SpecificationProperties;
        public Lazy|DataCollection|array $itemSpecificationProperties;
    
    


    public function includeProperties(): array
    {
        $arr= [
            'id',
            'code',
            'name',
            'description',
            'status',
            // 'meta',
            // 'item_id',
            // 'item_id',
            
            // 'songs' => count($this->songs) > 0,
        ];
        // if($this->type->isBuy()&& $this->status->isApproved())
        // $arr[]='installment_status';
        // now()->diffForHumans();
    return $arr;
    }
    public function exceptProperties(): array
    {
        $arr= [
            
            // 'title' => $this->title === 'Together Forever',
        ];
        // if($this->buy_quantity==0)
        // $arr[]='buy_quantity';
        // if($this->allocated_quantity==0)
        // $arr[]='allocated_quantity';
        // if($this->unallocated_quantity==0)
        // $arr[]='unallocated_quantity';
        
        // now()->diffForHumans();
    return $arr;
    }
}
