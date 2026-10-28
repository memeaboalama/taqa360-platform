<?php

namespace App\Domain\MarketPlace\resources\Branch;


use Illuminate\Http\Resources\Json\JsonResource;


class BranchWorkDayHourResource extends JsonResource
{
    public function toArray($request): array
    {

        return [
            'id' => $this->id,
            "from" =>  $this->from,//date_create_from_format('H:i:s', $this->from)->format('His.u'),
            "to" => $this->to,//date_create_from_format('H:i:s', $this->to)->format('His.u'),
            "status" => $this->status,
            "merchant_branch_id" => $this->merchant_branch_id,
            "work_day_id" => $this->work_day_id
        ];
    }
}
