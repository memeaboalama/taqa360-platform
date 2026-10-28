<?php

namespace App\Domain\MarketPlace\resources\Branch;


// use Collection;
use Illuminate\Http\Resources\Json\JsonResource;


class BranchWorkDayResource extends JsonResource
{
    public function toArray($request): array
    {

        return [
            'id' => $this->id,
            'name' => \Illuminate\Support\Str::of($this->day->name)->limit(20),
            'workDayHours' => BranchWorkDayHourResource::collection($this->workHours),
        ];
    }
}
