<?php

namespace App\Domain\ElmApp\resources;

use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Domain\MarketPlace\resources\Order\ElmOrderResource;
use App\Domain\User\resources\UserAddressResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Session;

class ReportsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {

        $data = [

            'code' => $this->code,
            'user_name' => $this->user?->name,
            'company_name'=> $this->merchant?->company_name,
            'branch_name' => $this->branch?->title,
            'subtotal'=> $this->subtotal,
            'discount'=> $this->discount,
            'total_after_discount' => (float) $this->subtotal - (float) $this->discount,
            'commission_percentage' => $this->merchant?->commissionPercentage,
            'commission' => number_format($this->commissionFromSub, 2),
            'do_to_merchant' => number_format($this->dueToMerchantFromSub, 2), 
            'date' => $this->created_at,
            'status' => $this->status,
            'payment_method' => $this->payment_method,

        ];

        return $data;
    }





}
