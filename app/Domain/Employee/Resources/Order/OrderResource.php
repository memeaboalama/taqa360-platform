<?php

namespace App\Domain\Employee\Resources\Order;

use App\Domain\User\resources\UserResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'status' => $this->status,
            'elm_upload_status' => $this?->elmOrder?->elm_upload_status,
            'rate' => $this->rate,
            'code' => $this->code,
            'total' => $this->total_after_vat,
            'created_date' => Carbon::parse($this->created_at)->format('d M Y'),
            'created_time' => Carbon::parse($this->created_at)->format('H:i a'),
            'schedulable' => $this->schedulable,
            'date' => Carbon::parse($this->date)->format('d M Y'),
            'time' => Carbon::parse($this->from)->format('H:i a'),
            'note' => $this->note,
            'driver_assigned' => ($this->driver_id) ? true : false,
            'customer' => [
                'name' => $this->user?->name,
                'email' => $this->user?->email,
                'phone' => $this->user?->PhoneNumber,
            ],
            'payment_summary'=>[
                'subtotal'=> $this->subtotal,
                'vat'=> number_format($this->vat,2),
                'delivery_fees'=> number_format($this->delivery_fee,2),
                'promocode'=>  abs($this->discount),
                'from_wallet' => number_format($this->from_wallet,2),
                'from_merchant_balance' => number_format($this->from_merchant_balance,2),
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
