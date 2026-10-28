<?php

namespace App\Domain\Catalog\Data\Attachment;


// use App\Domain\Catalog\Data\AttachmentProperty\AttachmentPropertyData;
// use App\Domain\Catalog\Data\Item\ItemData;
use App\Domain\Catalog\Models\Attachment;
use App\Support\Enum\StatusEnum;

use DateTime;
use Spatie\LaravelData\Attributes\AutoLazy;
use Spatie\LaravelData\Attributes\AutoWhenLoadedLazy;
use Spatie\LaravelData\Attributes\Computed;
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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

class AttachmentData extends Data
{


    public function __construct(
        public int $id,

        #[Computed]
        public bool $is_image,

        public string $type,
        public string $title,
        public ?string $description,
        public string $collection_name,
        public string $original_name,

        // Optional URLs (uncomment if needed)
        #[Computed]
        public ?string $url,

        #[Computed]
        public ?string $webp_url,

        #[Computed]
        public ?string $thumbnail_url,

        #[AutoWhenLoadedLazy('attachables')]
        public Lazy|null $sort_order,

        #[AutoWhenLoadedLazy('attachables')]
        public Lazy|null $is_primary,

        public string $created_at,
        public ?array $meta,
    ) {}

    public static function fromModel($attachment): self
    {
        return new self(
            id: $attachment->id,
            is_image: Str::startsWith($attachment->mime_type, 'image/'),
            type: $attachment->type,
            title: $attachment->title,
            description: $attachment->description,
            collection_name: $attachment->collection_name,
            original_name: $attachment->original_name,

            // Optional URLs
            // url: $attachment->type === 'file' ? Storage::disk($attachment->storage)->url($attachment->path) : $attachment->url,
            url: $attachment->type === 'file' ? url('storage/' . $attachment->path) : $attachment->url,
            // webp_url: $attachment->webp_path ? Storage::disk($attachment->storage)->url($attachment->webp_path) : null,
            webp_url: $attachment->webp_path ? url('storage/' . $attachment->webp_path) : null,
            // thumbnail_url: $attachment->thumbnail_path ? Storage::disk($attachment->storage)->url($attachment->thumbnail_path) : null,
            // thumbnail_url: $attachment->thumbnail_path ? url('storage/'.$attachment->thumbnail_path) : null,
            thumbnail_url: $attachment->thumbnail_path ? config('filesystems.disks.' . $attachment->storage . '.url', '/storage') . '/' . $attachment->thumbnail_path : null,

            sort_order: Lazy::whenLoaded('attachables', $attachment, fn() => $attachment->pivot->sort_order),
            is_primary: Lazy::whenLoaded('attachables', $attachment, fn() => (bool) $attachment->pivot->is_primary),

            created_at: $attachment->created_at->toDateTimeString(),
            meta: (isset($attachment->meta) && is_array($attachment->meta) && !empty($attachment->meta)) ? $attachment->meta : null,
        );
    }



    // #[WithCast(DateTimeInterfaceCast::class)]
    // public Lazy|DateTime $created_at;
    #[WithCast(DateTimeInterfaceCast::class)]
    public Lazy|DateTime $update_at;







    // public function includeProperties(): array
    // {
    //     $arr= [
    //         'id',
    //         'code',
    //         'name',
    //         'description',
    //         // 'value',
    //         'status',
    //         'meta',
    //         // 'attachment_id',
    //         'item_id',

    //         // 'songs' => count($this->songs) > 0,
    //     ];
    //     // if($this->type->isBuy()&& $this->status->isApproved())
    //     // $arr[]='installment_status';
    //     // now()->diffForHumans();
    // return $arr;
    // }
    public function exceptProperties(): array
    {
        $arr = [];
        if (!isset($this->thumbnail_url) || is_null($this->thumbnail_url))
            $arr[] = 'thumbnail_url';
        if (!isset($this->webp_url) || is_null($this->webp_url))
            $arr[] = 'webp_url';
        if (!isset($this->collection_name) || is_null($this->collection_name))
            $arr[] = 'collection_name';
        if (!isset($this->description) || is_null($this->description))
            $arr[] = 'description';
        if (!isset($this->original_name) || is_null($this->original_name))
            $arr[] = 'original_name';

        return $arr;
    }
}
