<?php

namespace App\Domain\Catalog\Data\Attachment;


use App\Domain\Catalog\Models\Attachment;

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

class DeleteAttachmentRequest extends Data
{
    // public bool $is_status_update = false;
    #[FromRouteParameter('attachment')]
    private ?Attachment $attachment;

    // #[FromRouteParameter('attachment_id')]
    // public int $id;
    // #[FromRouteParameter('item_id')]
    // public ?int $item_id;
    #[FromRouteParameterProperty('attachment','id')]
    public int $id;
    // #[FromRouteParameter('item_id')]
    #[FromRouteParameterProperty('item','id')]
    public ?int $item_id;



    public function setAttachment(Attachment $val)
    {
        // $this->is_status_update = false;
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
            'id' => 'required|integer|exists:attachments,id',
            'item_id' => ['nullable', 'integer', 'exists:items,id'],
            // 'name' => ['array'],
            // 'description' => ['nullable','array'],
            // 'meta' => ['nullable','array'],

            // 'start_at' => ['required_if:payment_method,installment', 'nullable'],
            // 'end_at' => ['required_if:payment_method,installment', 'nullable'],
            // 'start_at' => ['required_if:payment_method,installment', 'nullable', 'date_format:Y-m-d', 'after_or_equal:' . date('Y-m-d')],
            // 'end_at' => ['required_if:payment_method,installment', 'nullable', 'after_or_equal:start_at'],
            // // 'status' => ['nullable', 'string', 'in:rejected,approved'],
            // // 'rejected_reson' => ['required_if:status,rejected', 'nullable', 'string'],
            // 'status' => ['nullable', 'string', 'in:pending,cancelled'],
            // 'rejected_reson' => ['required_if:status,cancelled', 'nullable', 'string'],
            // 'payment_status' => ['nullable', 'string', 'in:unpaid,partial-paid,paid'],
            // 'share_price' => ['required','numeric', 'min:1'],
            // 'payment_method' => ['required', 'string', Rule::in(['instant','wallet', 'installment'])],
            // 'approval_status' => ['required|numeric', 'min:1', 'max:10'],
            // 'approval_status' => 'required|in:pending,rejected',
            // 'share_qty' => ['required','int', 'min:1', 'max:1000'],
            // 'amount' => ['required','numeric', 'min:1'],
            // 'paymanets_count' => ['required','int', 'min:1', 'max:10'],
            // 'timestamp' => ['required','datetime'],
        ];
    }
}
