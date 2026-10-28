<?php

namespace App\Domain\Catalog\Data\Attachment;

// use App\Domain\Catalog\Data\AttachmentProperty\CreateAttachmentPropertyData;
use App\Support\Enum\StatusEnum;
use DateTime;
use Spatie\LaravelData\Data;
use Illuminate\Support\Facades\Auth;
// use Spatie\LaravelData\Attributes\FromAuthenticatedUserProperty;
use Illuminate\Validation\Rule;
use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Attributes\FromRouteParameterProperty;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

class CreateAttachmentData extends Data
{
    // public int $created_by;

    #[FromRouteParameterProperty('item','id')]
    public ?int $item_id;


    // These are needed to identify the parent model (e.g., an Item)
    public int $attachable_id;
    public string $attachable_type;

    public ?string $title;
    public ?string $description;
    public ?string $collection_name;
    
    // One of these two is required
    public ?UploadedFile $file;
    public ?string $url;
    public ?array $meta;
    public function __construct()
    {
        
        
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
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'collection_name' => ['nullable', 'string', 'max:255'],
            
            // The 'file' is required if 'url' is not present.
            'file' => ['required_without:url', 'nullable', 'file', 'max:10240'], // 10MB Max

            // The 'url' is required if 'file' is not present.
            'url' => ['required_without:file', 'nullable', 'url', 'max:2048'],


        ];
    }
}
