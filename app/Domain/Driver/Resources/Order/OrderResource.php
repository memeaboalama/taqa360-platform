<?php

namespace App\Domain\Driver\Resources\Order;

use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Domain\MarketPlace\resources\Order\OrderItemResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Session;

class OrderResource extends JsonResource
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
            'id' => $this->id,
            "merchant_name" => $this->merchant_name,
            "branch" => $this->branch?->title,
            'status' => $this->status,
            "timing" => $this->status == MerchantOrderStatuses::PREVIOUS ? "history" : "current",

            'code' => $this->code,
            'date' => $this->date,
            'time' => [
                "from" => $this->from,
                "to" => $this->to
            ],
            "created_at" => Carbon::parse($this->created_at)->format("Y-m-d H:i A"),
            'payment_method' => $this->payment_method,

            "client" => [
                "id" => $this->user_id,
                "name" => $this->client_name ?? $this->user?->name,
                "phone" => $this->client_phone ?? $this->user?->phoneNumber,
            ],
            "location" => [
                "pickup" => [
                    "address" => $this->branch?->address_name,
                    "latitude" => $this->branch?->latitude,
                    "longitude" => $this->branch?->longitude,
                ],
                "drop" => [
                    "address" => $this->address?->address,
                    'addressInOrder' => $this->addressInOrder,
                    "latitude" => $this->client_lat ?? $this->address?->latitude,
                    "longitude" => $this->client_long ?? $this->address?->longitude,
                ],
            ],
            "order_details" => OrderItemResource::collection($this->orderItems),
            "summary" => [
                'subtotal'=> $this->subtotal,
                'vat'=> number_format($this->vat,2),
                'delivery_fees'=> number_format($this->delivery_fee,2),
                'promocode'=>  abs($this->discount),
                'from_wallet' => number_format($this->from_wallet,2),
                'from_merchant_balance' => number_format($this->from_merchant_balance,2),
                'total'=>number_format( $this->total_after_vat,2)  ,
            ],

            "driver_assigned" => $this->driver_id ? true : false,
            'gift' => $this->gift,
            'gift_confidential' => $this->gift_confidential,
            'ask_receipt' => $this->ask_receipt,
            'client_lat' => $this?->client_lat,
            'client_long' => $this?->client_long,

        ];
        if ($this->gift)
        {
            $data = array_merge($data, [
                'friend' => [
                    'name' => $this?->client_name,
                    'phone' => $this?->client_phone,
                ]
            ]);
        }

        if ($this->driver)
        {
            $data = array_merge($data, [
                'driver' => [
                    'name' => $this->driver?->name,
                    'phone' => $this->driver?->PhoneNumber,
                    'id' => $this->driver?->id,
                ]
            ]);
        }

        return $data;
    }


}
