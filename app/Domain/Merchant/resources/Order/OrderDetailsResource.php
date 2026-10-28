<?php

namespace App\Domain\Merchant\resources\Order;

use App\Domain\User\resources\UserAddressResource;
use App\Domain\User\resources\UserSettingResource;
use App\Domain\User\resources\WalletResource;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

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
            'code' => $this->code,
            'order_type' => $this->order_type,
            'services' => $this->services->pluck('service_name'),
            'customer' => [
                'name' => $this->client_name,
                'phone' => $this->client_phone,
                'dialcode' => $this->dialcode,
            ],

            'date' => Carbon::parse($this->date)->format('d M Y'),
            'time' => Carbon::parse($this->from)->format('h:i A'),

            'payment_method' => $this->payment_method,
            'payment_summary' => [
                'amount' => $this->subtotal,
                'vat' => $this->vat,
                'total' => $this->total_after_vat,
            ],

        ];

        if ($this->order_type == "mobile") {
            $data["car_location"] = new UserAddressResource($this->address()->with('city', 'distract')->first());
            $data["employee"] =[
                'name'=>$this->employee?->name,
                'phone'=>$this->employee?->phone,
                'dialcode'=>$this->employee?->dialcode,
                ];
        }


        return $data;


    }
}
