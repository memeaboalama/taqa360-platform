<?php

namespace App\Domain\MarketPlace\resources\ElmApp;

use App\Domain\AppBase\Resources\DistractResource;
use App\Domain\MarketPlace\Models\Favorite;
use App\Domain\MarketPlace\resources\Menu\MenuResource;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Session;
use JsonSerializable;


class MerchantResource extends JsonResource
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
            'merchant' => [
                'id' => $this['id'],
                'user_name' => $this['user_name'],
                'logo' => $this['logo'],
                'header_image' => $this['branch']?->cover,
                'company_name' => $this['company_name'],
                'description' => $this['description'],
                'branches' =>  collect($this['branches']->map(function ($branch) {
                    return $branch ? [
                        'id' => $branch->id,
                        'title' => $branch->title,
                        'description' => $branch->description,
                        'distance' =>number_format($branch->distance / 1000, 2) . " km",
                        'status' => ($branch->status) ? "open" : "closed",
                        'to' => date_create_from_format('H:i:s', $branch?->to)?->format('H:i:s') .' '. $branch?->meridiem,
                        'region_id' => $branch?->region?->id,
                        'city_id' => $branch?->city?->id,
                    ]:[];
                })),
            ],
        ];
    }
}
