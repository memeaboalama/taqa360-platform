<?php

namespace App\Domain\Catalog\Data\Item;


use App\Domain\Catalog\Models\Item;
use App\Support\Enum\StatusEnum;

use DateTime;
use Spatie\LaravelData\Data;
use Illuminate\Support\Facades\Auth;
use Spatie\LaravelData\Attributes\FromAuthenticatedUserProperty;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Attributes\FromRouteParameterProperty;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

class UpdateItemRequest extends Data
{
    public bool $is_status_update = false;
    private ?Item $item;

    #[FromRouteParameter('item_id')]
    public int $id;
    #[FromRouteParameter('item_id')]
    public ?int $item_id;


    #[WithCast(DateTimeInterfaceCast::class, format: [DATE_ISO8601_EXPANDED, DATE_ATOM, DATE_RFC3339_EXTENDED, 'Y-m-d'])]
    // #[WithTransformer(DateTimeInterfaceTransformer::class, format: [DATE_ISO8601_EXPANDED,DATE_ATOM,DATE_RFC3339_EXTENDED,'Y-m-d'])]
    // #[WithTransformer(DateTimeInterfaceTransformer::class, format: [DATE_ISO8601_EXPANDED,DATE_ATOM,DATE_RFC3339_EXTENDED])]
    // #[WithCast(DateTimeInterfaceCast::class)]
    public ?DateTime $update_at;
    public ?string $code;
    public ?mixed $name;
    public ?mixed $description;
    public ?array $meta;
    #[WithCast(EnumCast::class, type: StatusEnum::class)]
    public ?StatusEnum $status = StatusEnum::ACTIVE;
    public function __construct()
    {

        $this->update_at = now();
    }


    public function setItem(Item $val)
    {
        $this->is_status_update = false;
        $this->item = $val;

        // if (!isset($this->status) || !$this->payment_status || $this->payment_status == $val->payment_status)
        // $this->payment_status = $val->payment_status;
        if (!isset($this->status) || !$this->status) {
            // if ($val->status->isReview()) {
            //     $this->status = ApprovalStatus::PENDING;
            //     $this->is_status_update = true;
            // } else {
            $this->status = $val->status;
            $this->is_status_update = false;
            // }
            // } else if ($this->status == $val->status && $val->status->isReview()) {
            //     $this->status = ApprovalStatus::PENDING;
            //     $this->is_status_update = true;
        } else if ($this->status != $val->status) {
            $this->is_status_update = true;
        }
    }
    public function Item(): Item
    {
        if (!isset($this->item) || !$this->item)
            $this->setItem(Item::find($this->id)->load(['item', 'itemProperties']));
        return $this->item;
    }

    public static function authorize(): bool
    {
        // return Auth::user()->role === 'user';
        if (!Auth::hasUser())
            return false;
        $user = Auth::user();
        if ($user instanceof \Domain\Admin\Models\Admin)
            return true;
        if (Auth::guard('merchant')->check())
            return true;
        if (Auth::guard('merchant_employee')->check())
            return true;
        return false;
    }
    public static function rules(): array
    {
        return [
            'id' => 'required|integer|exists:items,id',
            'item_id' => ['nullable', 'integer', 'exists:items,id'],
            'code' => ['nullable', 'string'],
            'status' => 'nullable|in:active,inactive',
            // 'name' => ['array'],
            // 'description' => ['nullable','array'],
            // 'meta' => ['nullable','array'],
        ];
    }
}
