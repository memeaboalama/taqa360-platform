<?php

namespace App\Domain\Employee\Resources\Order;

use App\Domain\MarketPlace\resources\Order\OrderItemResource;
use App\Domain\User\resources\UserAddressResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Session;

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
        $data = [
            'id' => $this->id,
            'status' => $this->status,
            'elm_upload_status' => $this?->elmOrder?->elm_upload_status,
            'rate' => $this->rate,
            'driver_rate' => $this->driver_rate,
            'driver_assigned' => ($this->driver_id) ? true : false,
            'code' => $this->code,
            'items' => OrderItemResource::collection($this->orderItems),
            'customer' => [
                'name' => $this->user?->name,
                'email' => $this->user?->email,
                'phone' => $this->user?->PhoneNumber,
            ],
            'created_date' => Carbon::parse($this->created_at)->format('d M Y'),
            'created_time' => Carbon::parse($this->created_at)->format('H:i a'),
            'schedulable' => $this->schedulable,
            'date' => Carbon::parse($this->date)->format('d M Y'),
            'time' => Carbon::parse($this->from)->format('H:i a'),
            'payment_method' => $this->payment_method,
            'note' => $this->note,
            'payment_summary'=>[
                'subtotal'=> $this->subtotal,
                'vat'=> number_format($this->vat,2),
                'delivery_fees'=> number_format($this->delivery_fee,2),
                'promocode'=>  abs($this->discount),
                'total'=>number_format( $this->total_after_vat,2)  ,
            ],
            'gift' => $this->gift,
            'gift_confidential' => $this->gift_confidential,
            'ask_receipt' => $this->ask_receipt,
            'client_lat' => $this?->client_lat,
            'client_long' => $this?->client_long,            
            'service_type_id' => $this->service_type_id,
            'type_service_name' =>__('admin::home.'. $this->type_service_name()),

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
                ]
            ]);
        }
        return $data;
    }



}
