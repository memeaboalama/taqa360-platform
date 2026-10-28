<?php

namespace App\Domain\Catalog\Data\Item;

use App\Support\Enum\StatusEnum;
use DateTime;
use Spatie\LaravelData\Data;
use Illuminate\Support\Facades\Auth;
// use Spatie\LaravelData\Attributes\FromAuthenticatedUserProperty;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Attributes\FromRouteParameterProperty;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

class CreateItemData extends Data
{
    // public int $created_by;

    #[FromRouteParameter('item_id')]
    public ?int $item_id;


    #[WithCast(DateTimeInterfaceCast::class, format: [DATE_ISO8601_EXPANDED, DATE_ATOM, DATE_RFC3339_EXTENDED, 'Y-m-d'])]
    // #[WithTransformer(DateTimeInterfaceTransformer::class, format: [DATE_ISO8601_EXPANDED,DATE_ATOM,DATE_RFC3339_EXTENDED,'Y-m-d'])]
    // #[WithTransformer(DateTimeInterfaceTransformer::class, format: [DATE_ISO8601_EXPANDED,DATE_ATOM,DATE_RFC3339_EXTENDED])]
    // #[WithCast(DateTimeInterfaceCast::class)]
    public ?DateTime $created_at;
    public ?string $code;
    public ?mixed $name;
    public ?mixed $description;
    public ?array $meta;
    #[WithCast(EnumCast::class, type: StatusEnum::class)]
    public ?StatusEnum $status = StatusEnum::ACTIVE;
    public function __construct()
    {

        $this->created_at = now();
    }



    public static function authorize(): bool
    {
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
            'item_id' => ['nullable', 'integer', 'exists:items,id'],
            'code' => ['nullable', 'string'],
            'status' => 'required|in:active,inactive',
            // 'name' => ['array'],
            // 'description' => ['nullable','array'],
            // 'meta' => ['nullable','array'],


        ];
    }
}
