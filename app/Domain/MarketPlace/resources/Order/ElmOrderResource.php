<?php

namespace App\Domain\MarketPlace\resources\Order;

use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Domain\User\resources\UserAddressResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Session;

class ElmOrderResource extends JsonResource
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
            'elm_id' => $this?->elmOrder?->elm_id,
            'orderNumber' => $this->code,
            'authorityId' => $this?->elmOrder?->authorityId,
            'deliveryTime' => $this?->elmOrder?->deliveryTime,
            'regionId' => $this->branch?->city?->region?->elm_id,
            'cityId' => $this->branch?->city?->elm_id,
            'coordinates' => $this?->client_lat.','.$this?->client_long,
            'storetName' => $this->merchant_name,
            'storeLocation' => $this->branch?->latitude.','.$this->branch?->longitude,
            'categoryId' => $this?->elmOrder?->categoryId,
            'elm_upload_status' => $this?->elmOrder?->elm_upload_status,
            'orderDate' => $this->created_at,
            'elm_errors' => $this?->elmOrder?->elm_errors,
        ];
        
        if ($this->elm_upload_status == 2)
        {
            $data = array_merge($data, [
                'accept' => [
                    'acceptanceDateTime' => $this?->elmOrder?->acceptanceDateTime,
                ]
            ]);
        }
        if ($this->elm_upload_status == 3)
        {
            $data = array_merge($data, [
                'assigned' => [
                    'idNumber' => $this?->driver?->Id_number,
                ]
            ]);
        }

        if ($this->elm_upload_status == 4 && $this->status == MerchantOrderStatuses::PREVIOUS) 
        {
            $data = array_merge($data, [
                'executed_order' => [
                    'executed_dateTime' => $this?->updated_at,
                    'payment_method' => $this?->payment_method,
                    'total_after_vat' => $this?->total_after_vat,
                ]
            ]);
        }


        return $data;
    }

}
