<?php

namespace App\Domain\MarketPlace\resources\Cart;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Session;

class CartResource extends JsonResource
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
            'cart_id' => $this->id,
            'merchant_id' => $this->merchant_id,
            'branch_id' => $this->merchant_branch_id,
            'promocode' => $this->promocode,
            'cash_back_name' => $this->cash_back,
            'service_type_id' => $this->service_type_id,
            'items' => CartItemResource::collection($this->cartItems),
            'servicesTypes' => CartServiceTypeResource::collection($this->merchantBranchTypeServices),
            'branchData' => $this->branch,
            'payment_summary' => [
                'subtotal' => $this->subtotal,
                'vat' => number_format($this->tax,2),
                'delivery_fees' => (number_format($this->service_type_id==2 ? 0 : ($this->delivery_fee??0) ,2) - $this->deliveryFeeDiscount??0),
                'delivery_distance' => $this->deliveryDistance,
                'delivery_fee_discount' => $this->deliveryFeeDiscount??0,
                'promocode' =>  number_format($this->discount, 2),
                'cash_back' =>  number_format($this->returnCash, 2),
                'total' => number_format($this->total,2),
            ]
        ];
    }


}
