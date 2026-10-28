<?php

namespace App\Domain\Catalog\Data\SpecificationProperty;


use App\Domain\Catalog\Models\SpecificationProperty;
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

class UpdateSpecificationPropertyRequest extends Data
{
    public bool $is_status_update = false;
    private ?SpecificationProperty $specificationProperty;

    #[FromRouteParameter('specification_property_id')]
    public int $id;
    #[FromRouteParameter('specification_id')]
    public ?int $specification_id;
    #[FromRouteParameterProperty('item_id')]
    public ?int $item_id;


    // #[WithCast(DateTimeInterfaceCast::class, format: [DATE_ISO8601_EXPANDED, DATE_ATOM, DATE_RFC3339_EXTENDED, 'Y-m-d'])]
    #[WithCast(DateTimeInterfaceCast::class, format: ['Y-m-d'])]
    // #[WithTransformer(DateTimeInterfaceTransformer::class, format: [DATE_ISO8601_EXPANDED,DATE_ATOM,DATE_RFC3339_EXTENDED,'Y-m-d'])]
    // #[WithTransformer(DateTimeInterfaceTransformer::class, format: [DATE_ISO8601_EXPANDED,DATE_ATOM,DATE_RFC3339_EXTENDED])]
    // #[WithCast(DateTimeInterfaceCast::class)]
    public ?DateTime $update_at;
    public ?string $code;
    public ?string $name;
    // public ?mixed $name;
    // public ?mixed $description;
    // public ?mixed $value;
    public ?string $value;
    public ?array $meta;
    #[WithCast(EnumCast::class, type: StatusEnum::class)]
    public ?StatusEnum $status = StatusEnum::ACTIVE;
    public function __construct()
    {

        $this->update_at = now();
    }


    public function setSpecificationProperty(SpecificationProperty $val)
    {
        $this->is_status_update = false;
        $this->specificationProperty = $val;

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
    public function SpecificationProperty(): SpecificationProperty
    {
        if (!isset($this->specificationProperty) || !$this->specificationProperty)
            $this->setSpecificationProperty(SpecificationProperty::find($this->id)->load(['item', 'specificationPropertyProperties']));
        return $this->specificationProperty;
    }

    public static function authorize(): bool
    {
        // return Auth::user()->role === 'user';
        // if (!Auth::hasUser())
        //     return false;
        // $user = Auth::user();
        // if ($user instanceof \Domain\Admin\Models\Admin)
        //     return true;
        // if (Auth::guard('merchant')->check())
        //     return true;
        // if (Auth::guard('merchant_employee')->check())
        //     return true;
        // return false;
        return true;
    }
    public static function rules(): array
    {
        return [
            'id' => 'required|integer|exists:specification_properties,id',
            'specification_id' => ['nullable', 'integer', 'exists:specifications,id'],
            'item_id' => ['nullable', 'integer', 'exists:items,id'],
            'code' => ['nullable', 'string', ],
            'name' => ['required', 'string', 'max:255'],
            'status' => 'nullable|in:active,inactive',
            'value' => ['nullable', 'string'],
            // 'name' => ['array'],
            // 'value' => ['nullable','array'],
            // 'meta' => ['nullable','array'],
        ];
    }
}
