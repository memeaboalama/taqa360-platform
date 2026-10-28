<?php

namespace App\Domain\Catalog\Data\Specification;


use App\Domain\Catalog\Models\Specification;
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

class UpdateSpecificationRequest extends Data
{
    public bool $is_status_update = false;
    private ?Specification $specification;

    #[FromRouteParameter('specification_id')]
    public int $id;
    #[FromRouteParameter('item_id')]
    public ?int $item_id;


    
    public ?string $code;
    public ?string $name;
    public ?string $description;
    public ?array $meta;
    public ?array $properties;
    #[WithCast(EnumCast::class, type: StatusEnum::class)]
    public ?StatusEnum $status = StatusEnum::ACTIVE;
    public function __construct()
    {

        
    }


    public function setSpecification(Specification $val)
    {
        $this->is_status_update = false;
        $this->specification = $val;

        
        if (!isset($this->status) || !$this->status) {
        
            $this->status = $val->status;
            $this->is_status_update = false;
           
        } else if ($this->status != $val->status) {
            $this->is_status_update = true;
        }
    }
    public function Specification(): Specification
    {
        if (!isset($this->specification) || !$this->specification)
            $this->setSpecification(Specification::find($this->id)->load(['item', 'specificationProperties']));
        return $this->specification;
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
            'id' => 'required|integer|exists:specifications,id',
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
