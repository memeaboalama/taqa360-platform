<?php

namespace App\Domain\MarketPlace\resources\Order;

use App\Domain\User\resources\UserAddressResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {

        return [
            'id' => $this->id,
            'status' => $this->status,
            'elm_upload_status' => $this?->elmOrder?->elm_upload_status,
            'code' => $this->code,
            'merchant' => [
                'id'=>$this->merchant_id,
                'name'=>$this->merchant_name,
                'logo' => $this->merchant?->logo,
                'address' => $this->branch?->address_name,
            ],
            'address' => new UserAddressResource($this->address()->with('city','distract')->first()),
            'addressInOrder' => $this->addressInOrder,
            'date' => Carbon::parse($this->date)->format('d M Y'),
            'time' => [
                'form' => Carbon::parse($this->from)->format('h:i A'),
                'to' => Carbon::parse($this->to)->format('h:i A'),
            ],
            'payment_method' => $this->payment_method,
            'payment_status' => $this->payment?->status,
            'payment_summary' => [
                'amount' => $this->subtotal,
                'vat' => number_format($this->vat,2),
                'wallet_balance' => 0,
                'total' => $this->total_after_vat,
            ],
            'services' => OrderServiceResource::collection($this->services),            
            'service_type_id' => $this->service_type_id,
            'type_service_name' =>__('admin::home.'. $this->type_service_name()),

        ];
    }
}
