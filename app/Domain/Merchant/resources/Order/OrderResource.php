<?php

namespace App\Domain\Merchant\resources\Order;

use App\Domain\User\resources\UserSettingResource;
use App\Domain\User\resources\WalletResource;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

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
        return [
            'id' => $this->id,
            'status' => $this->status,
            'code' => $this->code,
            'order_type' => $this->order_type,
            'total' => $this->total_after_vat,
            'date' => Carbon::parse($this->date)->format('d M Y'),
            'time' => Carbon::parse($this->from)->format('h:i A'),


        ];
    }
}
