<?php

namespace App\Domain\Catalog\Data\SpecificationProperty;

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

class CreateSpecificationPropertyData extends Data
{
    // public int $created_by;
    #[FromRouteParameter('specification_id')]
    public int $specification_id;

    #[FromRouteParameter('item_id')]
    public ?int $item_id;

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

        $this->status= StatusEnum::ACTIVE;
        // $this->created_at = now();
    }



    public static function authorize(): bool
    {
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
            'item_id' => ['nullable', 'integer', 'exists:items,id'],
            'specification_id' => ['nullable', 'integer', 'exists:specifications,id'],
            'code' => ['nullable', 'string'],
            'name' => ['required', 'string', 'max:255'],
            'value' => ['nullable', 'string'],
            'status' => 'nullable|in:active,inactive',
            // 'name' => ['array'],
            // 'value' => ['nullable','array'],
            // 'meta' => ['nullable','array'],


        ];
    }
}
