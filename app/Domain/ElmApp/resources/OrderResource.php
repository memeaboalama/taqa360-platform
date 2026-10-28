<?php

namespace App\Domain\ElmApp\resources;

use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Domain\MarketPlace\resources\Order\ElmOrderResource;
use App\Domain\User\resources\UserAddressResource;
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
        $timing = '';
        switch ($this->status) {

            case MerchantOrderStatuses::UPCOMING :
                $timing = 'Current';
                break;
            case MerchantOrderStatuses::ON_WAY :
            case MerchantOrderStatuses::CURRENT:
                $timing = 'Upcoming';
                break;
            case MerchantOrderStatuses::PREVIOUS:
            case MerchantOrderStatuses::CANCELLED :
                $timing = 'Previous';
                break;
        }

        $data = [
            'id' => $this->id,
            'status' => $this->status,
            'user' => [
                'id' => $this->user?->id,
                'name' => $this->user?->name,
                'phone' => $this->user?->phone,
                'address' => new UserAddressResource($this->address()->with('city', 'distract')->first()),
                'addressInOrder' => $this->addressInOrder,
            ],
            'merchant' => [
                'id' => $this->merchant_id,
                'name' => $this->merchant_name,
                'logo' => $this->merchant?->logo,
                'phone' => $this->merchant?->phone,
                'branch_id' => $this->branch?->id,
                'branch_name' => $this->branch?->title,
                'address' => $this->branch?->address_name,
            ],
            'code' => $this->code,
            'created_date' => Carbon::parse($this->created_at)->format('d M Y'),
            'created_time' => Carbon::parse($this->created_at)->format('h:i A'),
            'schedulable' => $this->schedulable,
            'date' => Carbon::parse($this->date)->format('d M Y'),
            'time' => [
                'form' => Carbon::parse($this->from)->format('g:i a'),
                'to' => Carbon::parse($this->to)->format('g:i a'),
            ],
            "timeline"=>[
                "placed"=> true,
                "preparing"=> in_array($this->status, [
                    MerchantOrderStatuses::UPCOMING,
                    MerchantOrderStatuses::READY,
                    MerchantOrderStatuses::ON_WAY,
                    MerchantOrderStatuses::PREVIOUS]
                ),
                "on_way"=>in_array($this->status,[MerchantOrderStatuses::ON_WAY,MerchantOrderStatuses::PREVIOUS]),
                "delivered"=>in_array($this->status,[MerchantOrderStatuses::PREVIOUS]),
            ],
            'payment_method' => $this->payment_method,
            'payment_status' => $this->payment?->status,
            'payment_reference_number' => $this?->payment?->source_reference_number,
            //'items' => OrderItemResource::collection($this->orderItems),
            'timing' => $timing,
            'note' => $this->note,
            'rate' => $this->rate,
            'driver_rate' => $this->driver_rate,
            'payment_summary'=>[
                'subtotal'=> $this->subtotal,
                'vat'=> number_format($this->vat,2),
                'delivery_fees'=> number_format($this->delivery_fee,2),
                'promocode'=>  abs($this->discount),
                'from_wallet' => number_format($this->from_wallet,2),
                'from_merchant_balance' => number_format($this->from_merchant_balance,2),
                'total'=>number_format( $this->total_after_vat,2),
            ],
            'gift' => $this->gift,
            'gift_confidential' => $this->gift_confidential,
            'ask_receipt' => $this->ask_receipt,
            'client_lat' => $this?->client_lat,
            'client_long' => $this?->client_long,
            'service_type_id' => $this->service_type_id,
            'type_service_name' =>__('admin::home.'. $this->type_service_name()),
            'elm_order_info' => [
                'id' => $this->id,
                'elm_id' => $this?->elmOrder?->elm_id,
                'authorityId' => $this?->elmOrder?->authorityId,
                'deliveryTime' => $this?->elmOrder?->deliveryTime,
                'regionId' => $this->branch?->city?->region?->elm_id,
                'cityId' => $this->branch?->city?->elm_id,
                'storeLocation' => $this->branch?->latitude.','.$this->branch?->longitude,
                'categoryId' => $this?->elmOrder?->categoryId,
                'elm_upload_status' => $this?->elmOrder?->elm_upload_status,
                'elm_errors' => $this?->elmOrder?->elm_errors,
                'acceptanceDateTime' => $this?->elmOrder?->acceptanceDateTime,
                'executed_order' => [
                    'executed_dateTime' => $this?->updated_at,
                    'total_after_vat' => $this?->total_after_vat,
                ],
            ],
            'order_history' => collect($this['orders_history']->map(function ($event) {
                    return $event ? [
                        'id' => $event->id,
                        'action' => $event->action,
                        'reason' => $event->reason,
                        'time' => $event->created_at,
                        'changeable_type' => $event->changeable_type,
                        'changeable_id' => $event->changeable_id,
                    ]:[];
                })),

            'driver' => [
                'id' => $this->driver?->id,
                'idNumber' => $this?->driver?->Id_number,
                'name' => $this->driver?->name,
                'phone' => $this->driver?->PhoneNumber,
            ]


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

        return $data;
    }





}
