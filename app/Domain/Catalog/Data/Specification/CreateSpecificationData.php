<?php

namespace App\Domain\Catalog\Data\Specification;

use App\Domain\Catalog\Data\SpecificationProperty\CreateSpecificationPropertyData;
use App\Support\Enum\StatusEnum;
use DateTime;
use Spatie\LaravelData\Data;
use Illuminate\Support\Facades\Auth;
// use Spatie\LaravelData\Attributes\FromAuthenticatedUserProperty;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Attributes\FromRouteParameterProperty;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

class CreateSpecificationData extends Data
{
    // public int $created_by;

    #[FromRouteParameter('item_id')]
    public ?int $item_id;


    public ?string $code;
    public ?string $name;
    // public ?mixed $name;
    // public ?mixed $description;
    public ?string $description;
    public ?array $meta;
    
    public ?array $properties;
    #[WithCast(EnumCast::class, type: StatusEnum::class)]
    public ?StatusEnum $status = StatusEnum::ACTIVE;
    public function __construct()
    {
        
        $this->status=StatusEnum::ACTIVE;
        $this->meta=[];
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
            return true;
        // return false;
    }
    public static function rules(): array
    {
        return [
            'item_id' => ['nullable', 'integer', 'exists:items,id'],
            'code' => ['nullable', 'string'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => 'nullable|in:active,inactive',
            'properties' => ['nullable', 'array'],
            'properties.*.name' => ['required', 'string', 'max:255'],
            'properties.*.value' => ['nullable', 'string'],
            'properties.*.status' => ['nullable', Rule::in(['active', 'inactive'])],
            // 'name' => ['array'],
            // 'description' => ['nullable','array'],
            // 'meta' => ['nullable','array'],


        ];
    }
}
