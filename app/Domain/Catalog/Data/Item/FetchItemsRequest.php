<?php

namespace App\Domain\Catalog\Data\Item;

// use App\Application\Marketplace\Enums\MerchantInvestmentRequestStatuses;

use App\Support\Enum\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\FromAuthenticatedUserProperty;
use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;
use Illuminate\Support\Facades\Auth;
use Spatie\LaravelData\Attributes\FromRouteParameterProperty;

class FetchItemsRequest extends Data
{
    #[FromAuthenticatedUserProperty(null, 'id')]
    public int $update_by;
    #[FromRouteParameterProperty('item_id')]
    public ?int $item_id;
    // #[FromRouteParameter('investor_id')]
    // #[FromAuthenticatedUserProperty(null, 'investor.id')]
    // public int $investor_id;
    #[FromRouteParameter('item_id')]
    public null|int $item_id;
    
    #[WithCast(EnumCast::class, type: StatusEnum::class)]
    public ?StatusEnum $status;
    
    public ?int $per_page;
    public null|string $code;
    public null|string $search;
    public null|string $_sort;
    public null|string $_dir;
    public null|string $payment_method;
    /**
     * Determine if the user is authorized to make this request.
     */
    public static function authorize(): bool
    {
        return true;
        // return Auth::user()->role === 'user';
    }
    

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, Rule|array|string>
     */

     public static function rules(): array
    {
         $ALLOWED_STATUSES = ['draft', 'pending', 'confirmed', 'cancelled', 'ready', 'on-way', 'completed', 'expired'];
         $ALLOWED_ELM_STATUSES = ['accepted', 'rejected', 'needs_updating', 'accepted_by_merchant', 'driver_assigned', 'executed', 'cancelled','errors'];
         $ALLOWED_DATE_CONDITIONS = ['equal', 'gt', 'between'];

        return [
            'per_page' => 'nullable|integer|max:100',
            'item_id' => ['nullable', 'integer', 'exists:items,id'],
            // 'investor_id' => ['nullable', 'integer', 'exists:investors,id'],
            // 'type' =>['nullable', 'string', 'in:buy,sell'],
            // 'status' =>['nullable', 'string', 'in:pending,review,rejected,expired,approved,cancelled'],
            'status' =>['nullable', 'string', 'in:active,inactive'],
            // 'payment_status' =>['nullable', 'string', 'in:unpaid,partial-paid,paid'],
            // // 'status' => "nullable|string|in:" . implode(',', $ALLOWED_STATUSES),
            // // 'elm_status' => "nullable|string|in:" . implode(',', $ALLOWED_ELM_STATUSES),
            'date_condition' => "nullable|string|in:" . implode(',', $ALLOWED_DATE_CONDITIONS),
            'date' => 'required_with:date_condition|date_format:Y-m-d',
            'to_date' => 'required_if:date_condition,==,between|date_format:Y-m-d',
        ];
    }


    protected function prepareForValidation(): void
    {

    }
}
