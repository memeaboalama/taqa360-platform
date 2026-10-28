<?php

namespace App\Domain\Catalog\Data\Attachment;


use App\Domain\Catalog\Models\Attachment;
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

class UpdateAttachmentRequest extends Data
{
    public bool $is_status_update = false;
    #[FromRouteParameter('attachment')]
    private ?Attachment $attachment;

    // #[FromRouteParameter('attachment_id')]
    #[FromRouteParameterProperty('attachment','id')]
    public int $id;
    // #[FromRouteParameter('item_id')]
    #[FromRouteParameterProperty('item','id')]
    public ?int $item_id;


    public ?string $description;
    public ?string $url;

    public function __construct()
    {

        // $this->update_at = now();
        
    }


    public function setAttachment(Attachment $val)
    {
        $this->is_status_update = false;
        $this->attachment = $val;

       
    }
    public function Attachment(): Attachment
    {
        if (!isset($this->attachment) || !$this->attachment)
            $this->setAttachment(Attachment::find($this->id)->load(['item', 'attachmentProperties']));
        return $this->attachment;
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
            'id' => 'required|integer|exists:attachments,id',
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
